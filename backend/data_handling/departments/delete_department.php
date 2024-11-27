<?php
// Include the database connection file
include '../../db/dbconnect.php';

// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        
        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);
        
        // Sanitize the department_id to prevent SQL injection
        $department_id = intval($_POST['department_id']);

        // Delete department query
        $delete_department = "DELETE FROM departments WHERE department_id = $department_id";
        
        if (mysqli_query($con, $delete_department)) {
            // Set success message in session and redirect
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Department deleted successfully!';
            header("Location: departments.php");
            exit();
        } else {
            // Log the error and set an error message in session
            error_log("Database Error: " . mysqli_error($con));
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error deleting department. Please try again later.';
            header("Location: departments.php");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set an error message in session
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: departments.php");
        exit();
    }
}
?>
