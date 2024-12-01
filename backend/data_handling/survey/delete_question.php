<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get and sanitize the survey_id and question_id from the URL
        $survey_id = isset($_POST['survey_id']) ? mysqli_real_escape_string($con, $_POST['survey_id']) : '';
        $question_id = isset($_POST['question_id']) ? mysqli_real_escape_string($con, $_POST['question_id']) : '';

        // Validate inputs
        if (!empty($survey_id) && !empty($question_id)) {
            // Delete the question from the questions table
            $delete_query = "
                DELETE FROM questions
                WHERE question_id = '$question_id'
            ";

            // Attempt to execute the query
            if (mysqli_query($con, $delete_query)) {
                // If successful, set session variables for success message
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Question deleted successfully!';
                header("Location: view_survey.php?survey_id=$survey_id");
                exit();
            } else {
                // If query fails, log the error and set session variables for error message
                error_log("Database Error: " . mysqli_error($con)); // Log the error
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error deleting question. Please try again later.';
                header("Location: view_survey.php?survey_id=$survey_id");
                exit();
            }
        } else {
            // If required data is missing, set error message
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid request. Missing data.';
            header("Location: view_survey.php?survey_id=$survey_id");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: view_survey.php?survey_id=$survey_id");
        exit();
    }

} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: view_survey.php?survey_id=$survey_id");
    exit();
}
?>
