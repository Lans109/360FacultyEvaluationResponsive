<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate if criteria_id, survey_id, and csrf_token are not empty
    if (!empty($_POST['criteria_id']) && !empty($_POST['survey_id']) && !empty($_POST['csrf_token'])) {
        $criteria_id = mysqli_real_escape_string($con, $_POST['criteria_id']);
        $survey_id = mysqli_real_escape_string($con, $_POST['survey_id']);
        $csrf_token = $_POST['csrf_token'];

        // CSRF Token validation (ensure the token matches the session one)
        if ($csrf_token === $_SESSION['csrf_token']) {

            // Unset the CSRF token after validation
            unset($_SESSION['csrf_token']);

            // Prepare the delete query
            $delete_query = "DELETE FROM questions_criteria WHERE criteria_id = '$criteria_id'";

            // Execute the delete query
            if (mysqli_query($con, $delete_query)) {
                // If successful, set session variables for success message
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Criteria deleted successfully!';
                header("Location: view_survey.php?survey_id=$survey_id");
                exit();
            } else {
                // Log the error and show a user-friendly message
                error_log("Database Error: " . mysqli_error($con));
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error deleting criteria. Please try again later.';
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
        // If any required parameter is empty, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid request. Please try again.';
        header("Location: view_survey.php?survey_id=$survey_id");
        exit();
    }
} else {
    // Redirect back if the request method is not POST
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method. Please try again.';
    header("Location: view_survey.php?survey_id=$survey_id");
    exit();
}
?>
