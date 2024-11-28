<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the faculty_id is set in the URL and form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation and use
        unset($_SESSION['csrf_token']);

        // Sanitize the faculty_id from the URL
        $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);

        // Proceed to delete the record from the faculty table
        $delete_query = "DELETE FROM faculty WHERE faculty_id = '$faculty_id'";

        // Execute the delete query
        if (mysqli_query($con, $delete_query)) {
            // Optionally, delete from the faculty_courses table if necessary
            $delete_courses_query = "DELETE FROM faculty_courses WHERE faculty_id = '$faculty_id'";
            mysqli_query($con, $delete_courses_query);

            // Set success message and redirect back to faculty management page
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Faculty member deleted successfully.';
            header("Location: faculty.php");
            exit();
        } else {
            // Log the error and set an error message in the session
            error_log("Database Error: " . mysqli_error($con)); // Log error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error deleting faculty member. Please try again later.';
            header("Location: faculty.php");
            exit();
        }

    } else {
        // If CSRF token validation fails
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: faculty.php");
        exit();
    }

} else {
    // If the request method is not POST or faculty_id is missing
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request. Please try again.';
    header("Location: faculty.php");
    exit();
}
?>