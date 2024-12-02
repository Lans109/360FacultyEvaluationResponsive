<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $faculty_department = mysqli_real_escape_string($con, $_POST['department_id']);

        // Validate that all required fields are provided
        if ($faculty_id && $first_name && $last_name && $faculty_department) {
            // SQL query to update faculty information
            $update_query = "UPDATE 
                                faculty 
                            SET 
                                first_name = '$first_name', 
                                last_name = '$last_name', 
                                email = '$email', 
                                phone_number = '$phone_number', 
                                department_id = '$faculty_department',
                                updated_at = NOW()
                            WHERE 
                                faculty_id = '$faculty_id'";

            // Attempt to execute the query
            if (mysqli_query($con, $update_query)) {
                // Set success message and redirect
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Faculty updated successfully!';
                header("Location: faculty.php");
                exit();
            } else {
                // Log the error and set error message in session
                error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error updating faculty. Please try again later.';
                header("Location: faculty.php");
                exit();
            }
        } else {
            // Set error message in session if fields are missing
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Please fill all required fields.';
            header("Location: faculty.php");
            exit();
        }
    } else {
        // If CSRF token doesn't match, set error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: faculty.php");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: faculty.php");
    exit();
}
?>