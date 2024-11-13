<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $criteria_id = $_POST['criteria_id'];
    $description = $_POST['description'];

    // Update criteria in the database
    $update_query = "UPDATE questions_criteria SET description = '$description' WHERE criteria_id = $criteria_id";
    
    if (mysqli_query($con, $update_query)) {
        // Redirect to survey.php if successful
        header("Location: survey.php");
        exit();
    } else {
        echo "Error updating criteria: " . mysqli_error($con);
    }
}
?>
