<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section = $_POST['section'];
    $course_id = $_POST['course_id'];
    $period_id = 1; //Sample Period

    // Insert new course into the database
    $insert_section = "INSERT INTO course_sections (section, course_id, period_id) VALUES ('$section', '$course_id', '$period_id')";
    
    if (mysqli_query($con, $insert_section)) {
        header("Location: sections.php"); // Redirect back to the course admin panel
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
