<?php
session_start();
include("databasecon.php");

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] != 'faculty') {
    header("Location: faculty_login.php");
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

// Fetch evaluations
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
    JOIN faculty_departments fd ON fc.faculty_id = fd.faculty_id  
    JOIN faculty f ON fd.faculty_id = f.faculty_id  
    WHERE fe.faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $faculty_email);
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
    $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)");
    $stmt->bind_param("sis", $comment, $evaluation_id, $faculty_email);
    $stmt->execute();
    $stmt->close();

    header("Location: faculty_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <title>Faculty Dashboard</title>
</head>
<style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000; /* Higher than other page elements */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            margin-top: 10%;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
</style>
<body>
    <div class="header">
        <h1>Faculty Dashboard</h1>
        <nav>
            <a href="faculty_dashboard.php">Courses</a>
            <a href="userprofile.php">Profile</a>
            <a style="text-decoration: underline; font-weight: bold;" href="faculty_evaluation.php">Evaluate</a>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </nav>
    </div>
    <div class="container">
        <div class="card">
            <h2>Evaluation Tasks</h2>
            <?php if (!empty($evaluations)): ?>
                <table border="1">
                    <tr>
                        <th>Faculty Name</th>
                        <th>Course Code</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($evaluations as $evaluation): ?>
                        <tr>
                            <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?></td>
                            <td><?php echo $evaluation['course_code']; ?></td>
                            <td><?php echo $evaluation['is_completed'] ? 'Completed' : 'Pending'; ?></td>
                            <td><?php echo $evaluation['created_at']; ?></td>
                            <td><?php echo $evaluation['end_date']; ?></td>
                            <td>
                                <?php if (!$evaluation['is_completed']): ?>
                                    <!-- Redirect to evaluation page -->
                                    <a href="evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Start Evaluation</a>
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