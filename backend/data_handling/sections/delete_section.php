<?php
// Include the database connection file
include '../../db/dbconnect.php';

// Start the session
session_start();

// Check if the course_section_id is set in the URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_section_id'])) {
    $course_section_id = $_POST['course_section_id'];

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Sanitize the input to prevent SQL injection
        $course_section_id = mysqli_real_escape_string($con, $course_section_id);

        // SQL query to delete the section from course_sections
        $delete_course_query = "DELETE FROM course_sections WHERE course_section_id = '$course_section_id'";

        // Attempt to execute the delete query
        if (mysqli_query($con, $delete_course_query)) {
            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Section deleted successfully!';
            header("Location: sections.php");
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error deleting section. Please try again later.';
            header("Location: sections.php");
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: sections.php");
        exit();
    }

} else {
    // If no course_section_id is provided, set session variables for error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid section ID.';
    header("Location: sections.php");
    exit();
}
?>
