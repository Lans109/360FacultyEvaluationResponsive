<?php
// faculty_evaluation.php
session_start();
include('../db/databasecon.php');

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] != 'faculty') {
    header("Location: ../login-pages/faculty_login.php");
    exit();
}

// Use session variables instead of hardcoded values
$faculty_email = $_SESSION['email'];

// Fetch faculty profile
$sql = "SELECT * FROM faculty WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();

// Fetch co-faculty evaluations (exclude self-evaluations)
$sql = "
    SELECT 
        e.evaluation_id, 
        e.course_section_id, 
        e.survey_id, 
        fe.is_completed, 
        e.created_at, 
        ep.end_date, 
        c.course_code, 
        f.first_name AS faculty_first_name, 
        f.last_name AS faculty_last_name
    FROM evaluations e
    JOIN faculty_evaluations fe ON e.evaluation_id = fe.evaluation_id
    JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
    JOIN courses c ON cs.course_id = c.course_id
    JOIN evaluation_periods ep ON e.period_id = ep.period_id
    JOIN faculty f ON fc.faculty_id = f.faculty_id
    JOIN surveys s ON e.survey_id = s.survey_id
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)
    AND s.target_role != 'Self'  -- Exclude self-evaluations
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$co_faculty_evaluations = $result->fetch_all(MYSQLI_ASSOC);

// Fetch self-evaluation
$sql_self = "
    SELECT 
        e.evaluation_id, 
        e.survey_id, 
        fe.is_completed, 
        e.created_at, 
        ep.end_date
    FROM evaluations e
    JOIN faculty_evaluations fe ON e.evaluation_id = fe.evaluation_id
    JOIN evaluation_periods ep ON e.period_id = ep.period_id
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)
    AND e.survey_id IN (SELECT survey_id FROM surveys WHERE target_role = 'Self')
";
$stmt = $conn->prepare($sql_self);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$self_evaluation = $result->fetch_assoc();

// Check if any self-evaluation has been completed
$sql_self_check = "
    SELECT COUNT(*) AS completed_count 
    FROM faculty_evaluations fe
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)
    AND fe.is_completed = 1
    AND fe.evaluation_id IN (SELECT evaluation_id FROM evaluations WHERE survey_id IN (SELECT survey_id FROM surveys WHERE target_role = 'Self'))
";
$stmt = $conn->prepare($sql_self_check);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$self_completed_count = $result->fetch_assoc()['completed_count'];

// Check if any co-faculty evaluation has been completed
$sql_co_faculty_check = "
    SELECT COUNT(*) AS completed_count 
    FROM faculty_evaluations fe
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)
    AND fe.is_completed = 1
    AND fe.evaluation_id NOT IN (SELECT evaluation_id FROM evaluations WHERE survey_id IN (SELECT survey_id FROM surveys WHERE target_role = 'Self'))
";
$stmt = $conn->prepare($sql_co_faculty_check);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$co_faculty_completed_count = $result->fetch_assoc()['completed_count'];

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_responses'])) {
    // Get the evaluation_id and survey_id
    $evaluation_id = $_POST['evaluation_id'];
    $is_self_evaluation = $_POST['is_self_evaluation']; // Flag to determine if it's self-evaluation

    // Insert each response into the responses table
    foreach ($_POST['responses'] as $question_id => $rating) {
        // Insert response into the database
        $stmt = $conn->prepare("INSERT INTO responses (evaluation_id, question_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $evaluation_id, $question_id, $rating);
        $stmt->execute();
        $stmt->close();
    }

    // Update the evaluation status
    if ($is_self_evaluation) {
        // Only mark the self-evaluation as completed
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1 WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)");
    } else {
        // Mark the co-faculty evaluation as completed
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1 WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)");
    }
    $stmt->bind_param("is", $evaluation_id, $faculty_email);
    $stmt->execute();
    $stmt->close();

    // Redirect to faculty evaluation page
    header("Location: faculty/faculty_evaluation.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles.css">
    <title>Faculty Dashboard</title>

</head>

<body>
    <div class="header">
        <div class="nav-title">
            <h1>
                Evaluation
            </h1>
        </div>
        <?php include 'faculty_navbar.php' ?>
    </div>
    <div class="container">
  <!-- Self-Evaluation Section -->
  <div class="card">
            <h2>Self-Evaluation</h2>
            <?php if (!empty($self_evaluation)): ?>
                <p><strong>Status: </strong>
                    <?php
                    // Check if the self-evaluation is completed
                    if ($self_evaluation['is_completed'] == 1) {
                        echo 'Self Evaluation Completed';
                    } else {
                        echo 'Pending';
                        // Provide the link to start the evaluation only if not completed
                        echo '<br><a href="../evaluationpage.php?evaluation_id=' . $self_evaluation['evaluation_id'] . '&is_self_evaluation=1">Start Self-Evaluation</a>';
                    }
                    ?>
                </p>
            <?php else: ?>
                <p>No self-evaluation available.</p>
            <?php endif; ?>
        </div>

        <!-- Co-Faculty Evaluation Section -->
        <div class="card">
            <h2>Co-Faculty Evaluation</h2>
            <?php if (!empty($co_faculty_evaluations)): ?>
                <table border="1">
                    <tr>
                        <th>Faculty Name</th>
                        <th>Course Code</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($co_faculty_evaluations as $evaluation): ?>
                        <tr>
                            <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?></td>
                            <td><?php echo $evaluation['course_code']; ?></td>
                            <td><?php echo $evaluation['is_completed'] ? 'Completed' : 'Pending'; ?></td>
                            <td><?php echo $evaluation['created_at']; ?></td>
                            <td><?php echo $evaluation['end_date']; ?></td>
                            <td>
                                <?php if (!$evaluation['is_completed'] && $co_faculty_completed_count === 0): ?>
                                    <!-- Redirect to evaluation page -->
                                    <a href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>&is_self_evaluation=0">Start Evaluation</a>
                                <?php else: ?>
                                    <button disabled>Completed or Evaluation Already Started</button>
                                <?php endif; ?>
                            </td>
                        </tr>

                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <tr>
                                <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?>
                                </td>
                                <td><?php echo $evaluation['course_code']; ?></td>
                                <td><?php echo $evaluation['is_completed'] ? 'Completed' : 'In Progress'; ?></td>
                                <td><?php echo date('F j, Y, g:i a', strtotime($evaluation['created_at'])); ?></td>
                                <td><?php echo date('F j, Y', strtotime($evaluation['end_date'])); ?></td>
                                <td>
                                    <?php if (!$evaluation['is_completed']): ?>
                                        <a
                                            href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Start</a>
                                    <?php else: ?>
                                        <button disabled>Completed</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <!-- mobile view -->
                    <div class="ttc-container">
                        <?php foreach ($evaluations as $evaluation): ?>
                            <div class="ttc">
                                <div class="ttc-header">
                                    <h3><?php echo htmlspecialchars($evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']); ?>
                                    </h3>
                                </div>
                                <div class="ttc-body">
                                    <p><strong>Course Code:</strong> <?php echo htmlspecialchars($evaluation['course_code']); ?>
                                    </p>
                                    <p><strong>Created At:</strong>
                                        <?php echo date('F j, Y, g:i a', strtotime($evaluation['created_at'])); ?></p>
                                    <p><strong>Deadline:</strong>
                                        <?php echo date('F j, Y', strtotime($evaluation['end_date'])); ?></p>
                                </div>
                                <div class="ttc-footer">
                                    <p><strong>Status:</strong>
                                        <?php echo $evaluation['is_completed'] ? 'Completed' : 'In Progress'; ?></p>
                                    <?php if (!$evaluation['is_completed']): ?>
                                        <a href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>"
                                            class="btn">Start</a>
                                    <?php else: ?>
                                        <button class="btn" disabled>Completed</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No evaluations available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>