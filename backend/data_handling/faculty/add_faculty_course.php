<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get and sanitize form data to prevent SQL injection
        $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);
        $course_section_id = mysqli_real_escape_string($con, $_POST['course_section_id']);

        // Validate input to ensure both faculty ID and course section ID are provided
        if (empty($faculty_id) || empty($course_section_id)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Please provide both faculty ID and course section ID.';
            header("Location: view_faculty_profile.php?faculty_id=$faculty_id");
            exit();
        }

        // SQL query to insert the faculty-course assignment into the database
        $query = "
            INSERT INTO faculty_courses (faculty_id, course_section_id) 
            VALUES ('$faculty_id', '$course_section_id')
        ";

        // Attempt to execute the query
        if (mysqli_query($con, $query)) {
            // If successful, set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course assigned successfully!';
            header("Location: view_faculty_profile.php?faculty_id=$faculty_id");
            exit();
        } else {
            // If query fails, log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error assigning course. Please try again later.';
            header("Location: view_faculty_profile.php?faculty_id=$faculty_id");
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: view_faculty_profile.php?faculty_id=$faculty_id");
        exit();
    }
} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: view_faculty_profile.php?faculty_id=$faculty_id");
    exit();
}
?>
