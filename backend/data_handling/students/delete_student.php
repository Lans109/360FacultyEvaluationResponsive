<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Check if the 'student_id' is set and valid
        if (isset($_POST['student_id']) && !empty($_POST['student_id'])) {
            // Sanitize the student_id to prevent SQL injection
            $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

            // Create the delete query
            $delete_query = "DELETE FROM students WHERE student_id = '$student_id'";

            // Execute the query
            if (mysqli_query($con, $delete_query)) {
                // If successful, set success message in the session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Student removed successfully.';
                header("Location: students.php");
                exit();
            } else {
                // If query fails, log the error and set an error message
                error_log("Database Error: " . mysqli_error($con)); // Log the error
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error: Unable to remove student. Please try again later.';
                header("Location: students.php");
                exit();
            }
        } else {
            // If student_id is missing, set an error message
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Student ID is missing.';
            header("Location: students.php");
            exit();
        }

    } else {
        // If CSRF token is invalid, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: Invalid CSRF token. Please try again.';
        header("Location: students.php");
        exit();
    }

} else {
    // If the request method is not POST, set an error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Error: Invalid request method.';
    header("Location: students.php");
    exit();
}
?>
