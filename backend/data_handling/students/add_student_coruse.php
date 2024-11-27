<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request method is POST and the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get and sanitize form data to prevent SQL injection
        $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
        $course_section_id = mysqli_real_escape_string($con, $_POST['course_section_id']);

        // Validate input to ensure both student ID and course section ID are provided
        if (empty($student_id) || empty($course_section_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Please provide both student ID and course section ID.';
            header("Location: view_student_profile.php?student_id=$student_id");
            exit();
        }

        // SQL query to insert the student-course enrollment into the database
        $query = "
            INSERT INTO student_courses (student_id, course_section_id) 
            VALUES ('$student_id', '$course_section_id')
        ";

        // Attempt to execute the query
        if (mysqli_query($con, $query)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Enrollment successful!';
            header("Location: view_student_profile.php?student_id=$student_id");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error enrolling student. Please try again later.';
            header("Location: view_student_profile.php?student_id=$student_id");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: view_student_profile.php?student_id=$student_id");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: view_student_profile.php?student_id=" . (isset($_POST['student_id']) ? $_POST['student_id'] : ''));
    exit();
}
?>
