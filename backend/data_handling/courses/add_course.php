<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $course_description = $_POST['course_description'];
    $department_id = $_POST['department_id'];

    // Insert new course into the database
    $insert_course = "INSERT INTO courses (course_name, course_code, course_description, department_id) VALUES ('$course_name', '$course_code', '$course_description', '$department_id')";
    
    if (mysqli_query($con, $insert_course)) {
        header("Location: courses.php"); // Redirect back to the course admin panel
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
