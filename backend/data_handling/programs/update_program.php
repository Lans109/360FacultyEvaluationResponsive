<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'];
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $program_description = $_POST['program_description'];
    $department_id = $_POST['department_id'];

    $update_query = "UPDATE programs SET 
        program_name = '$program_name', 
        program_code = '$program_code', 
        program_description = '$program_description', 
        department_id = '$department_id'
        WHERE program_id = '$program_id'";

    if (mysqli_query($con, $update_query)) {
        echo "Program updated successfully!";
    } else {
        echo "Error updating program: " . mysqli_error($con);
    }
}
header("Location: programs.php");
exit();
?>
