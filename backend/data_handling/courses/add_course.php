<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $course_name = mysqli_real_escape_string($con, $_POST['course_name']);
        $course_code = mysqli_real_escape_string($con, $_POST['course_code']);
        $course_description = mysqli_real_escape_string($con, $_POST['course_description']);
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);

        // Validate input to ensure all fields are filled
        if (empty($course_name) || empty($course_code) || empty($course_description) || empty($department_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // SQL query to insert the new course into the database
        $insert_course = "INSERT INTO courses (course_name, course_code, course_description, department_id) 
                          VALUES ('$course_name', '$course_code', '$course_description', '$department_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_course)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course added successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add course. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
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
