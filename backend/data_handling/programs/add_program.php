<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $program_description = $_POST['program_description'];
    $department_id = $_POST['department_id'];

    // Insert new course into the database
    $insert_course = "INSERT INTO programs (program_name, program_code, program_description, department_id) VALUES ('$program_name', '$program_code', '$program_description', '$department_id')";
    
    if (mysqli_query($con, $insert_course)) {
        header("Location: programs.php"); // Redirect back to the course admin panel
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
