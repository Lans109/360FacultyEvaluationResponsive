<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get question_id and survey_id from the URL
    $survey_id = isset($_GET['survey_id']) ? mysqli_real_escape_string($con, $_GET['survey_id']) : '';
    $question_id = isset($_GET['question_id']) ? mysqli_real_escape_string($con, $_GET['question_id']) : '';

    // Validate inputs
    if (!empty($survey_id) && !empty($question_id)) {
        // Delete the question from the database
        $delete_query = "
            DELETE FROM questions
            WHERE question_id = '$question_id' AND survey_id = '$survey_id'
        ";

        if (mysqli_query($con, $delete_query)) {
            // Redirect to the survey questions page with a success message
            header("Location: view_survey.php?survey_id=$survey_id&status=success");
        } else {
            // Log the error and show a user-friendly message
            error_log("Error deleting question: " . mysqli_error($con));
            header("Location: view_survey.php?survey_id=$survey_id&status=error");
        }
    } else {
        // Redirect with an error message if required data is missing
        header("Location: view_survey.php?survey_id=$survey_id&status=invalid");
    }
} else {
    // Redirect with an error message if the request method is invalid
    header("Location: view_survey.php?status=method_error");
}
?>
