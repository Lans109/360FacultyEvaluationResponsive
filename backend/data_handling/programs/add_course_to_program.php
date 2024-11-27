<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request method is POST and the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get and sanitize form data to prevent SQL injection
        $program_id = mysqli_real_escape_string($con, $_POST['program_id']);
        $course_id = mysqli_real_escape_string($con, $_POST['course_id']);

        // Validate input to ensure both program ID and course ID are provided
        if (empty($program_id) || empty($course_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Please provide both program ID and course ID.';
            header("Location: view_program_courses.php?program_id=$program_id");
            exit();
        }

        // SQL query to insert the program-course association into the database
        $insert_query = "INSERT INTO program_courses (program_id, course_id) VALUES ('$program_id', '$course_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_query)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course assigned successfully!';
            header("Location: view_program_courses.php?program_id=$program_id");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error assigning course. Please try again later.';
            header("Location: view_program_courses.php?program_id=$program_id");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: view_program_courses.php?program_id=$program_id");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: view_program_courses.php?program_id=$program_id");
    exit();
}
?>
