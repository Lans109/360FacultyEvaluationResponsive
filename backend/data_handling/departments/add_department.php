<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_name = $_POST['department_name'];
    $department_code = $_POST['department_code'];
    $department_description = $_POST['department_description'];
    $chair_id = $_POST['chair_id'];

    // Insert department
    $insert_department = "INSERT INTO departments (department_name, department_code, department_description) VALUES ('$department_name', '$department_code', '$department_description')";
    if (mysqli_query($con, $insert_department)) {
        $department_id = mysqli_insert_id($con);

        // Assign program chair if selected
        if (!empty($chair_id)) {
            // Update the program chair with the new department_id
            $assign_chair = "UPDATE program_chairs SET department_id = $department_id WHERE chair_id = $chair_id";
            mysqli_query($con, $assign_chair);
        }

        header("Location: departments.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
