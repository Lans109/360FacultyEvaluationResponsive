<?php
// student_evaluation.php
session_start();
include('../db/databasecon.php');

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] != 'students') {
    header("Location: ../login-pages/student_login.php");
    exit();
}

// Use session variables instead of hardcoded values
$student_email = $_SESSION['email'];

// Fetch student profile
$sql = "SELECT * FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetch evaluations
$sql = "
    SELECT e.evaluation_id, e.course_section_id, e.survey_id, se.is_completed, e.created_at, 
           ep.end_date,
           f.first_name AS faculty_first_name, f.last_name AS faculty_last_name, 
           c.course_code
    FROM evaluations e
    JOIN students_evaluations se ON e.evaluation_id = se.evaluation_id
    JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
    JOIN faculty f ON fc.faculty_id = f.faculty_id
    JOIN courses c ON cs.course_id = c.course_id
    JOIN evaluation_periods ep ON e.period_id = ep.period_id
    WHERE se.student_id = (SELECT student_id FROM students WHERE email = ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();
$evaluations = $result->fetch_all(MYSQLI_ASSOC);

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_responses'])) {
    // Get the evaluation_id from the form
    $evaluation_id = $_POST['evaluation_id'];
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Insert each response into the responses table
    foreach ($_POST['responses'] as $question_id => $rating) {
        // Insert response into the database
        $stmt = $conn->prepare("INSERT INTO responses (evaluation_id, question_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $evaluation_id, $question_id, $rating);
        $stmt->execute();
        $stmt->close();
    }

    // Update the evaluation status to "completed" and save the comment
    $stmt = $conn->prepare("UPDATE students_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND student_id = (SELECT student_id FROM students WHERE email = ?)");
    $stmt->bind_param("sis", $comment, $evaluation_id, $student_email);
    $stmt->execute();
    $stmt->close();

    header("Location: student_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles.css">
    <title>Student Dashboard</title>
</head>

<body>

    <div class="header">
        <div class="nav-title">
            <h1>
                Evaluation Page
            </h1>
        </div>
        <?php include 'student_navbar.php' ?>
    </div>
    <div class="container">
        <div class="card">
            <h2>Evaluation Tasks</h2>
            <?php if (!empty($evaluations)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Faculty Name</th>
                            <th>Course Code</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <tr>
                                <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?>
                                </td>
                                <td><?php echo $evaluation['course_code']; ?></td>
                                <td>
                                    <?php echo $evaluation['is_completed'] ? '<span style="color: green;">Completed</span>' : '<span style="color: red;">Pending</span>'; ?>
                                </td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($evaluation['created_at'])); ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($evaluation['end_date'])); ?></td>
                                <td>
                                    <?php if (!$evaluation['is_completed']): ?>
                                        <a href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Start
                                            Evaluation</a>
                                    <?php else: ?>
                                        <button disabled>Completed</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No evaluations available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>