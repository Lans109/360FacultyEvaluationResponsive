<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if criteria_id is set
if (isset($_POST['criteria_id'])) {
    $criteria_id = $_POST['criteria_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM questions_criteria WHERE criteria_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $criteria_id);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            // Redirect back to the question management page with success message
            header("Location: survey.php?message=Criteria+deleted+successfully");
        } else {
            // Redirect back with error message
            header("Location: survey.php?message=Error+deleting+criteria");
        }

        mysqli_stmt_close($stmt);
    } else {
        // Redirect back with error message
        header("Location: survey.php?message=Error+preparing+delete+query");
    }

    mysqli_close($con);
} else {
    // Redirect back if criteria_id is not provided
    header("Location: survey.php?message=No+criteria+id+provided");
}
?>
