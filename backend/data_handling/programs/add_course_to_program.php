<?php
// Include database connection
include '../../db/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $program_id = $_POST['program_id'];
    $course_id = $_POST['course_id'];

    // Insert into program_course table
    $insert_query = "INSERT INTO program_courses (program_id, course_id) VALUES (?, ?)";
    $stmt = $con->prepare($insert_query);
    $stmt->bind_param("ii", $program_id, $course_id);

    if ($stmt->execute()) {
        // Redirect back to the program management page with success message
        header("Location: view_program_courses.php?program_id=$program_id");
    } else {
        // Redirect back with error message
        header("Location: view_program_courses.php?program_id=$program_id");
    }

    $stmt->close();
}
$con->close();
?>
