<?php
// Start the session for CSRF protection
session_start();

// Include database connection
include '../../db/dbconnect.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        
        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);
        
        // Get the form data and sanitize it to prevent SQL injection
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = $_POST['password'];
        $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']); // New field
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);
        
        // Validate required fields are not empty
        if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($phone_number) || empty($department_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: faculty.php");
            exit();
        }

        // Hash the password for security
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new faculty into the database
        $insert_query = "
            INSERT INTO faculty (first_name, last_name, email, username, password_hash, phone_number, department_id) 
            VALUES ('$first_name', '$last_name', '$email', '$username', '$password_hash', '$phone_number', '$department_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_query)) {
            // Set success message in session and redirect
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Faculty member added successfully.';
            header("Location: faculty.php");
            exit();
        } else {
            // Log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add faculty member. Please try again later.';
            header("Location: faculty.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: faculty.php");
        exit();
    }
} else {
    // If no POST request is made, set error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: faculty.php");
    exit();
}
?>
