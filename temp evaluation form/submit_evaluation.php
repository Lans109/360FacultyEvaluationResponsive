<?php
// Database connection
$host = "localhost";
$username = "root";  
$password = ""; 
$dbname = "evalsystem"; 
$period_id = 1;
$con = new mysqli($host, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $faculty_id = $_POST['faculty_id'];
    $survey_id = $_POST['survey_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $course_section_id = $_POST['course_section_id'];
    $survey_comment = $_POST['survey_comment']; // Get the comment from the form

    // Prepare the SQL for the evaluations table
    $sqlEvaluation = "
        INSERT INTO evaluations (course_section_id, survey_id, period_id, created_at)
        VALUES (?, ?, ?, NOW())
    ";
    $stmtEvaluation = $con->prepare($sqlEvaluation);
    $stmtEvaluation->bind_param("iii", $course_section_id, $survey_id, $period_id);
    $stmtEvaluation->execute();

    // Get the last inserted evaluation_id
    $evaluation_id = $con->insert_id;

    // Prepare to insert ratings into the responses table
    $sqlResponse = "
        INSERT INTO responses (evaluation_id, question_id, rating)
        VALUES (?, ?, ?)
    ";

    // Assuming student_id is known; you might want to adjust this according to your application logic
    $student_id = 3; // Replace this with the actual student_id, e.g., from session or form input

    // Loop through the ratings and insert them into the responses table
    foreach ($_POST['ratings'] as $question_id => $rating) {
        $stmtResponse = $con->prepare($sqlResponse);
        $stmtResponse->bind_param("iii", $evaluation_id, $question_id, $rating);
        $stmtResponse->execute();
        $stmtResponse->close(); // Close after each iteration to reset the statement
    }

    // Insert the evaluation entry for the student in the evaluation_students table with the comment
    $sqlEvalStudent = "
        INSERT INTO students_evaluations (evaluation_id, student_id, date_evaluated, time_evaluated, comments)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmtEvalStudent = $con->prepare($sqlEvalStudent);
    $stmtEvalStudent->bind_param("iisss", $evaluation_id, $student_id, $date, $time, $survey_comment);
    $stmtEvalStudent->execute();

    echo "Evaluation submitted successfully!";
} else {
    // Redirect back to the selection form if accessed directly
    header("Location: faculty_survey_selection.php");
    exit();
}

// Close the connections
$stmtEvaluation->close();
$stmtEvalStudent->close();
$con->close();
?>
