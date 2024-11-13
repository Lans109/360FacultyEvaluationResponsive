<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_section_id = $_POST['course_section_id'];
    $course_id = $_POST['course_id'];
    $section = $_POST['section'];

    $update_query = "UPDATE course_sections SET 
        section = '$section', 
        course_id = '$course_id'
        WHERE course_section_id = '$course_section_id'";

    if (mysqli_query($con, $update_query)) {
        echo "Section updated successfully!";
    } else {
        echo "Error updating program: " . mysqli_error($con);
    }
}
header("Location: sections.php");
exit();
?>
