<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and the necessary parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course_section_id'], $_POST['csrf_token'])) {

    // Get the student ID, course section ID, and CSRF token from the POST request
    $student_id = $_POST['student_id'];
    $course_section_id = $_POST['course_section_id'];
    $csrf_token = $_POST['csrf_token'];

    // Validate CSRF token
    if (isset($_SESSION['csrf_token']) && $csrf_token === $_SESSION['csrf_token']) {

        // Unset the CSRF token after successful validation
        unset($_SESSION['csrf_token']);

        // Validate and sanitize input
        if (!empty($student_id) && !empty($course_section_id)) {
            $student_id = mysqli_real_escape_string($con, $student_id);
            $course_section_id = mysqli_real_escape_string($con, $course_section_id);

            // Prepare the SQL query to delete the enrollment
            $delete_query = "
                DELETE FROM student_courses 
                WHERE student_id = '$student_id' AND course_section_id = '$course_section_id'";

            // Execute the query
            if (mysqli_query($con, $delete_query)) {
                // Set success message and redirect
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Enrollment removed successfully!';
                header("Location: view_student_profile.php?student_id=" . $student_id);
                exit();
            } else {
                // Log error and set failure message
                error_log("Database Error: " . mysqli_error($con)); // Log error for debugging
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error: Unable to remove enrollment. Please try again later.';
                header("Location: view_student_profile.php?student_id=" . $student_id);
                exit();
            }
        } else {
            // Missing or invalid input
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Invalid input provided.';
            header("Location: view_student_profile.php?student_id=" . $student_id);
            exit();
        }
    } else {
        // Invalid CSRF token
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: Invalid CSRF token. Please try again.';
        header("Location: view_student_profile.php?student_id=" . $student_id);
        exit();
    }
} else {
    // Invalid request method or missing parameters
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method or missing parameters.';
    header("Location: view_student_profile.php?student_id=" . $student_id);
    exit();
}
?>
