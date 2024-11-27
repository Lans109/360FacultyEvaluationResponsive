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
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <title>Faculty Dashboard</title>
<style>
        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", Arial, sans-serif;
            background: linear-gradient(135deg, #7D0006, #D3D3D3);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            color: #000;
            line-height: 1.6;
            margin: 0;
        }

        /* Header Section */
        .header {
            background: #7D0006;
            padding: 1.5rem 0;
            text-align: center;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Header Navigation Links */
        .header nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            background-color: #7D0006;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header nav a:hover {
            color: #000;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            box-shadow: 3px 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Container */
        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 1rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #f4f4f4;
        }

        th {
            background-color: #7D0006;
            color: #fff;
            font-size: 1.1rem;
        }

        td {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        /* Button Styles */
        td a,
        td button {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }

        td a {
            background-color: #7D0006;
            transition: background-color 0.3s;
        }

        td a:hover {
            background-color: #D3D3D3;
            color: #7D0006;
        }

        td button {
            background-color: #7D0006;
            border: none;
            cursor: not-allowed;
        }

        td button:disabled {
            background-color: #999;
        }

        /* Profile Card */
        .card {
            text-align: center;
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.25);
        }

        /* Button Styles */
        button {
            background: #7D0006;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #D3D3D3;
            color: #7D0006;
        }
</style>
</head>
<body>
    <div class="header">
        <h1>Faculty Dashboard</h1>
        <?php include 'faculty_navbar.php' ?>
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
                                <td><?php echo $evaluation['faculty_first_name'] . ' ' . $evaluation['faculty_last_name']; ?></td>
                                <td><?php echo $evaluation['course_code']; ?></td>
                                <td><?php echo $evaluation['is_completed'] ? 'Completed' : 'In Progress'; ?></td>
                                <td><?php echo date('F j, Y, g:i a', strtotime($evaluation['created_at'])); ?></td>
                                <td><?php echo date('F j, Y', strtotime($evaluation['end_date'])); ?></td>
                                <td>
                                    <?php if (!$evaluation['is_completed']): ?>
                                        <a href="../evaluationpage.php?evaluation_id=<?php echo $evaluation['evaluation_id']; ?>">Start</a>
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