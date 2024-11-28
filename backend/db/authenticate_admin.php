<?php
// Start the session
session_start();

// Include the database connection and configuration files
include_once "../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = trim($_POST['password']);

        // Validate input to ensure all fields are filled
        if (empty($username) || empty($password)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Please fill in all fields!';
            header("Location: ../../index.php");
            exit();
        }

        // SQL query to get the admin details from the database
        $query = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($con, $query);

        // Check if the query was successful and if the admin exists
        if ($result && mysqli_num_rows($result) === 1) {
            $admin = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $admin['password_hash'])) {
                // Store admin info in session
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['role'] = 'admin';

                // Redirect to admin dashboard
                header('Location: ../data_handling/dashboard/dashboard.php');
                exit();
            } else {
                // If password doesn't match, set error message and redirect back
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Invalid username or password';
                header("Location: ../../index.php");
                exit();
            }
        } else {
            // If admin is not found, set error message and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid username or password';
            header("Location: ../../index.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: ../../index.php");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: ../../index.php");
    exit();
}
?>
