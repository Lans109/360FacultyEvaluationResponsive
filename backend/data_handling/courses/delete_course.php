<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the request method is GET and if 'course_id' is set
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Sanitize the course_id to prevent SQL injection
    $course_id = mysqli_real_escape_string($con, $course_id);

    // SQL query to delete the course from the database
    $delete_course_query = "DELETE FROM courses WHERE course_id = '$course_id'";

    // Attempt to execute the query
    if (mysqli_query($con, $delete_course_query)) {
        // If successful, set session variables for success message
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Course deleted successfully!';
        header("Location: courses.php");
        exit();
    } else {
        // If query fails, log the error and set session variables for error message
        error_log("Database Error: " . mysqli_error($con)); // Log the error
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error deleting course. Please try again later.';
        header("Location: courses.php");
        exit();
    }
} else {
    // If no course_id is passed or incorrect request method, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid course ID or request method.';
    header("Location: courses.php");
    exit();
}
?>
