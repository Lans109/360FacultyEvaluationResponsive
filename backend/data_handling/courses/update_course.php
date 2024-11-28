<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
        $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
        $course_name = mysqli_real_escape_string($con, $_POST['course_name']);
        $course_code = mysqli_real_escape_string($con, $_POST['course_code']);
        $course_description = mysqli_real_escape_string($con, $_POST['course_description']);
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);

        // SQL query to update the course
        $update_query = "UPDATE courses SET 
            course_name = '$course_name', 
            course_code = '$course_code', 
            course_description = '$course_description', 
            department_id = '$department_id' 
            WHERE course_id = '$course_id'";

        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course updated successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating course. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>