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

        // Get and sanitize the POST data
        $survey_id = mysqli_real_escape_string($con, $_POST['survey_id']);
        $criteria_id = mysqli_real_escape_string($con, $_POST['criteria_id']);
        $question_code = mysqli_real_escape_string($con, $_POST['question_code']);
        $question_text = mysqli_real_escape_string($con, $_POST['question_text']);

        // Insert the new question into the questions table
        $insert_query = "
            INSERT INTO questions (criteria_id, question_code, question_text)
            VALUES ('$criteria_id', '$question_code', '$question_text')
        ";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_query)) {
            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Question added successfully!';
            header("Location: view_survey.php?survey_id=$survey_id");
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error adding question. Please try again later.';
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
