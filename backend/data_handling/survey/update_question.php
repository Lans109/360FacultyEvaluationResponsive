<?php
// Include the database connection file
include '../../db/dbconnect.php';

// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
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

            // Attempt to execute the update query
            if (mysqli_query($con, $update_query)) {
                // If successful, set session variables for success message
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Question updated successfully!';
                header("Location: view_survey.php?survey_id=$survey_id");
                exit();
            } else {
                // If query fails, log the error and set session variables for error message
                error_log("Database Error: " . mysqli_error($con)); // Log the error
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error updating question. Please try again later.';
                header("Location: view_survey.php?survey_id=$survey_id");
                exit();
            }

        } else {
            // If validation fails, set session variables for invalid data message
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid input data. Please check your form and try again.';
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
