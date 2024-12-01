<?php
session_start();

// Include database connection
include_once "../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data and sanitize it to prevent SQL injection
    $username_or_email = mysqli_real_escape_string($con, $_POST['username_or_email']);
    $password = trim($_POST['password']);

    // Validate input: check if username/email or password is empty
    if (empty($username_or_email) || empty($password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Please enter both username/email and password.';
        header("Location: ../index.php");
        exit();
    }

    // SQL query to get the admin details from the database (checking both username and email)
    $query = "SELECT *, CONCAT(first_name, ' ', last_name) AS admin_name FROM admins WHERE username = '$username_or_email' OR email = '$username_or_email' LIMIT 1";
    $result = mysqli_query($con, $query);

    // Check if the query was successful and if the admin exists
    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verify the password using password_verify (assuming hashed passwords)
        if (password_verify($password, $admin['password'])) {
            // Store admin information in session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_key'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['admin_name'];
            $_SESSION['account_type'] = 'admin';

            // Redirect to the admin dashboard or home page
            header("Location: data_handling/dashboard/dashboard.php");
            exit();
        } else {
            // If password doesn't match, set error message and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid username/email or password.';
            header("Location: ../index.php");
            exit();
        }
    } else {
        // If admin account is not found, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid username/email or password.';
        header("Location: ../index.php");
        exit();
    }
} else {
    // If the form is not submitted correctly, redirect to login page
    header("Location: ../index.php");
    exit();
}
?>
