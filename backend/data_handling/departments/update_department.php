<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $department_code = $_POST['department_code'];
    $department_description = $_POST['department_description'];
    $chair_id = $_POST['chair_id'];

    $query = "UPDATE departments SET 
        department_name = '$department_name', 
        department_code = '$department_code', 
        department_description = '$department_description' 
        WHERE department_id = '$department_id'";

    if (mysqli_query($con, $query)) {
        $currentChairQuery = "SELECT chair_id FROM program_chairs WHERE department_id = '$department_id'";
        $currentChairResult = mysqli_query($con, $currentChairQuery);
        $currentChairId = mysqli_fetch_assoc($currentChairResult)['chair_id'] ?? null;

        if (!empty($chair_id) && $chair_id != $currentChairId) {
            if ($currentChairId) {
                $clearCurrentChairQuery = "UPDATE program_chairs SET department_id = NULL WHERE chair_id = '$currentChairId'";
                mysqli_query($con, $clearCurrentChairQuery);
            }

            $updateChairQuery = "UPDATE program_chairs SET department_id = '$department_id' WHERE chair_id = '$chair_id'";
            if (!mysqli_query($con, $updateChairQuery)) {
                echo "Error updating program chair: " . mysqli_error($con);
            }
        }

        header("Location: departments.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}
?>
