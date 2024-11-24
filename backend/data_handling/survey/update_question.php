<?php
include '../../db/dbconnect.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $survey_id = isset($_POST['survey_id']) ? mysqli_real_escape_string($con, $_POST['survey_id']) : '';
    $criteria_id = isset($_POST['criteria_id']) ? mysqli_real_escape_string($con, $_POST['criteria_id']) : '';
    $question_id = isset($_POST['question_id']) ? mysqli_real_escape_string($con, $_POST['question_id']) : '';
    $question_code = isset($_POST['question_code']) ? mysqli_real_escape_string($con, $_POST['question_code']) : '';
    $question_text = isset($_POST['question_text']) ? mysqli_real_escape_string($con, $_POST['question_text']) : '';

    // Validate inputs
    if ($criteria_id && $question_id && $question_code && $question_text) {
        $update_query = "
            UPDATE questions
            SET question_code = '$question_code',
                question_text = '$question_text'
            WHERE question_id = '$question_id' AND criteria_id = '$criteria_id'
        ";

        if (mysqli_query($con, $update_query)) {
            // Redirect with a success status
            header("Location: view_survey.php?survey_id=$survey_id&status=success");
            exit;
        } else {
            // Log the error and redirect with an error status
            error_log("Error updating question: " . mysqli_error($con));
            header("Location: view_survey.php?survey_id=$survey_id&status=error");
            exit;
        }
    } else {
        // Redirect with an invalid data status
        header("Location: view_survey.php?survey_id=$survey_id&status=invalid");
        exit;
    }
} else {
    // Redirect with an invalid method status
    header("Location: view_survey.php?status=method_error");
    exit;
}
?>
