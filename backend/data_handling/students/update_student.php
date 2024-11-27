<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Sanitize the input data to prevent SQL injection
        $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $program_id = mysqli_real_escape_string($con, $_POST['program_id']); // Program ID sanitization

        // SQL query to update the student's information
        $update_query = "UPDATE students SET 
            email = '$email', 
            first_name = '$first_name', 
            last_name = '$last_name', 
            program_id = '$program_id' 
            WHERE student_id = '$student_id'";

        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            // Set success message in the session
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Student updated successfully.';
            header("Location: students.php");
            exit();
        } else {
            // Log the error and set error message in the session
            error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating student. Please try again later.';
            header("Location: students.php");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: students.php");
        exit();
    }

} else {
    // If the request method is not POST, set an error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: students.php");
    exit();
}
?>
