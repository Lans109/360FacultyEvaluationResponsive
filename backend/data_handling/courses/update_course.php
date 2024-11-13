<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $course_description = $_POST['course_description'];
    $department_id = $_POST['department_id'];

    $update_query = "UPDATE courses SET 
        course_name = '$course_name', 
        course_code = '$course_code', 
        course_description = '$course_description', 
        department_id = '$department_id' 
        WHERE course_id = '$course_id'";

    if (mysqli_query($con, $update_query)) {
        echo "Course updated successfully!";
    } else {
        echo "Error updating course: " . mysqli_error($con);
    }
}
header("Location: courses.php");
exit();
?>
