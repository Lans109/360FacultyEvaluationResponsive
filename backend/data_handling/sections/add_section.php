<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $section = mysqli_real_escape_string($con, $_POST['section']);
        $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
        $period_id = $_SESSION['period_id']; // Sample Period (can be dynamically set based on your logic)

        // Validate input to ensure all fields are filled
        if (empty($section) || empty($course_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: sections.php");
            exit();
        }

        // SQL query to insert the new section into the database
        $insert_section = "INSERT INTO course_sections (section, course_id, period_id) 
                           VALUES ('$section', '$course_id', '$period_id')";

        // Attempt to execute the query
        if (mysqli_query($con, $insert_section)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Section added successfully!';
            header("Location: sections.php");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add section. Please try again later.';
            header("Location: sections.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: sections.php");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: sections.php");
    exit();
}
?>
