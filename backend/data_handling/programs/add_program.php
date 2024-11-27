<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $program_name = mysqli_real_escape_string($con, $_POST['program_name']);
        $program_code = mysqli_real_escape_string($con, $_POST['program_code']);
        $program_description = mysqli_real_escape_string($con, $_POST['program_description']);
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);

        // Validate input to ensure all fields are filled
        if (empty($program_name) || empty($program_code) || empty($program_description) || empty($department_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: programs.php");
            exit();
        }

        // SQL query to insert the new program into the database
        $insert_program = "INSERT INTO programs (program_name, program_code, program_description, department_id) 
                           VALUES ('$program_name', '$program_code', '$program_description', '$department_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_program)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Program added successfully!';
            header("Location: programs.php");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add program. Please try again later.';
            header("Location: programs.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
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
