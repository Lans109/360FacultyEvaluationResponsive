<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
        $criteria_id = mysqli_real_escape_string($con, $_POST['criteria_id']);
        $description = mysqli_real_escape_string($con, $_POST['description']);

        // Update criteria in the database
        $update_query = "UPDATE questions_criteria SET description = '$description' WHERE criteria_id = $criteria_id";
        
        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Criteria updated successfully!';
            header("Location: survey.php");
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating criteria. Please try again later.';
            header("Location: survey.php");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: survey.php");
        exit();
    }

} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: survey.php");
    exit();
}
?>
