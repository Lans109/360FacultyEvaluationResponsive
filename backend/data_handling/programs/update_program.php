<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
        $program_id = mysqli_real_escape_string($con, $_POST['program_id']);
        $program_name = mysqli_real_escape_string($con, $_POST['program_name']);
        $program_code = mysqli_real_escape_string($con, $_POST['program_code']);
        $program_description = mysqli_real_escape_string($con, $_POST['program_description']);
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);

        // SQL query to update the program
        $update_query = "UPDATE programs SET 
            program_name = '$program_name', 
            program_code = '$program_code', 
            program_description = '$program_description', 
            department_id = '$department_id' 
            WHERE program_id = '$program_id'";

        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Program updated successfully!';
            header("Location: programs.php");
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating program. Please try again later.';
            header("Location: programs.php");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: programs.php");
        exit();
    }

} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: programs.php");
    exit();
}
?>
