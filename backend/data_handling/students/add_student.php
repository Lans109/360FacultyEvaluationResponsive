<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $program_id = mysqli_real_escape_string($con, $_POST['program_id']);

        // Validate input to ensure all fields are filled
        if (empty($username) || empty($email) || empty($first_name) || empty($last_name) || empty($phone_number) || empty($password) || empty($program_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: students.php");
            exit();
        }

        // Hash the password for security
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new student into the database
        $query = "INSERT INTO students (username, email, first_name, last_name, phone_number, password_hash, program_id) 
                  VALUES ('$username', '$email', '$first_name', '$last_name', '$phone_number', '$password_hash', '$program_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $query)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Student added successfully!';
            header("Location: students.php");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add student. Please try again later.';
            header("Location: students.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: students.php");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: students.php");
    exit();
}
?>
