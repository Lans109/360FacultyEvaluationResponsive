<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if criteria_id is set
if (isset($_GET['criteria_id'])) {
    $criteria_id = $_GET['criteria_id'];
    $survey_id = $_GET['survey_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM questions_criteria WHERE criteria_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $criteria_id);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            // Redirect back to the question management page with success message
            header("Location: view_survey.php?survey_id=$survey_id");
        } else {
            // Redirect back with error message
            header("Location: view_survey.php?survey_id=$survey_id");
        }

        mysqli_stmt_close($stmt);
    } else {
        // Redirect back with error message
        header("Location: view_survey.php?survey_id=$survey_id");
    }

    mysqli_close($con);
} else {
    // Redirect back if survey_id is not provided
    header("Location: view_survey.php?survey_id=$survey_id");
}
?>
