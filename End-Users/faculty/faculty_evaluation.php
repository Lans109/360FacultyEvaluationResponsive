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

$department_id = $faculty['department_id'];

// Fetch Self Evaluation
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
        f.last_name AS faculty_last_name,
        f.profile_image,
        f.email,
        f.department_id,
        c.course_name
    FROM evaluations e
    JOIN faculty_evaluations fe ON e.evaluation_id = fe.evaluation_id
    JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
    JOIN courses c ON cs.course_id = c.course_id
    JOIN evaluation_periods ep ON e.period_id = ep.period_id
    JOIN faculty f ON fc.faculty_id = f.faculty_id
    JOIN surveys s ON e.survey_id = s.survey_id
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?) 
    AND ep.status = 'active'
    AND s.target_role = 'Self'  -- Exclude self-evaluations
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $faculty_email);
$stmt->execute();
$result = $stmt->get_result();
$self_evaluation = $result->fetch_all(MYSQLI_ASSOC);

$sql_peer = "
    SELECT 
        e.evaluation_id, 
        e.course_section_id, 
        e.survey_id, 
        fe.is_completed, 
        e.created_at, 
        ep.end_date, 
        c.course_code, 
        f.first_name AS faculty_first_name, 
        f.last_name AS faculty_last_name,
        f.profile_image,
        f.email,
        f.department_id,
        c.course_name
    FROM evaluations e
    JOIN faculty_evaluations fe ON e.evaluation_id = fe.evaluation_id
    JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
    JOIN courses c ON cs.course_id = c.course_id
    JOIN evaluation_periods ep ON e.period_id = ep.period_id
    JOIN faculty f ON fc.faculty_id = f.faculty_id
    JOIN surveys s ON e.survey_id = s.survey_id
    WHERE 
        ep.status = 'active'
        AND s.target_role = 'Faculty'  -- For peer evaluations
        AND f.department_id = $department_id
";
$stmt_peer = $conn->prepare($sql_peer);
$stmt_peer->execute();
$result_peer = $stmt_peer->get_result();
$peer_evaluation = $result_peer->fetch_all(MYSQLI_ASSOC);


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
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1 WHERE evaluation_id = ?");
    } else {
        // Mark the co-faculty evaluation as completed
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1 WHERE evaluation_id = ?");
    }
    $stmt->bind_param("i", $evaluation_id);
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

        <!-- Co-Faculty Evaluation Section -->
        <div class="card">
            <h2>Faculty Evaluation</h2>
            <?php if (!empty($self_evaluation)): ?>

                <div class="ttc-container">
                    <?php foreach ($self_evaluation as $evaluation): ?>
                        <div class="ttc">
                            <!-- Card Header -->
                            <div class="ttc-header">
                                <h3>
                                    <?php echo htmlspecialchars($evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']); ?>
                                </h3>
                            </div>

                            <!-- Card Body -->
                            <div class="ttc-body">
                                <img width="200px" src="../<?php echo $evaluation['profile_image'] ?>" alt="profile_image">
                                <p><?php echo htmlspecialchars($evaluation['course_code']); ?></p>
                                <?php echo htmlspecialchars($evaluation['email']); ?><br>
                                <?php echo htmlspecialchars($evaluation['course_name']); ?>
                            </div>

                            <!-- Card Footer -->
                            <div class="ttc-footer">
                                <?php if (!$evaluation['is_completed']): ?>
                                    <a href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>&is_self_evaluation=0"
                                        class="btn">
                                        Start Evaluation
                                    </a>
                                <?php else: ?>
                                    <button class="btn" disabled>
                                        <?php echo $evaluation['is_completed']
                                            ? 'Completed'
                                            : 'Already Evaluated One Faculty'; ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <table>
                    <tr>
                        <th>Faculty Name</th>
                        <th>Email</th>
                        <th>Course Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                    <!-- Self Evaluations -->
                    <tr>
                        <td colspan="5"><strong>Self Evaluations</strong></td>
                    </tr>
                    <?php foreach ($self_evaluation as $evaluation): ?>
                        <tr>
                            <td><img width="100px" src="../<?php echo $evaluation['profile_image'] ?>" alt="profile_image"></td>
                            <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?><br>
                                <?php echo $evaluation['email']; ?>
                            </td>
                            <td><?php echo $evaluation['course_code']; ?></td>
                            <td>
                                <?php echo $evaluation['is_completed'] ? '<span style="color: green;">Completed</span>' : '<span style="color: red;">Pending</span>'; ?>
                            </td>
                            <td>
                                <?php if (!$evaluation['is_completed']): ?>
                                    <a
                                        href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Evaluate</a>
                                <?php else: ?>
                                    <button disabled>Completed</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Peer Evaluations -->
                    <tr>
                        <td colspan="5"><strong>Peer Evaluations</strong></td>
                    </tr>
                    <?php foreach ($peer_evaluation as $evaluation): ?>
                        <tr>
                            <td><img width="100px" src="../<?php echo $evaluation['profile_image'] ?>" alt="profile_image"></td>
                            <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?><br>
                                <?php echo $evaluation['email']; ?>
                            </td>
                            <td><?php echo $evaluation['course_code']; ?></td>
                            <td>
                                <?php echo $evaluation['is_completed'] ? '<span style="color: green;">Completed</span>' : '<span style="color: red;">Pending</span>'; ?>
                            </td>
                            <td>
                                <?php if (!$evaluation['is_completed']): ?>
                                    <a
                                        href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Evaluate</a>
                                <?php else: ?>
                                    <button disabled>Completed</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>


            <?php else: ?>
                <p>No evaluations available at the moment.</p>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>