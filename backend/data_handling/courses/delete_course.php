<?php
// Include the database connection file
include '../../db/dbconnect.php';

// Start the session
session_start();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation and use
        unset($_SESSION['csrf_token']);

        // Check if the 'faculty_id' and 'course_section_id' are set and not empty
        if (isset($_SESSION['faculty_id'])) {
            $faculty_id = $_SESSION['faculty_id']; // Use the faculty_id stored in the session
            $course_section_id = mysqli_real_escape_string($con, $_POST['course_section_id']); // Sanitize the course section ID

            // Proceed to delete the record from the faculty_courses table
            $delete_query = "
                DELETE FROM faculty_courses 
                WHERE faculty_id = '$faculty_id' AND course_section_id = '$course_section_id'";

            // Execute the query and check if it was successful
            if (mysqli_query($con, $delete_query)) {
                // If successful, set a success message in the session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Course removed successfully!';

                // Redirect to the faculty profile page, passing the faculty_id to the URL
                header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id);
                exit();
            } else {
                // If query fails, log the error and set an error message in the session
                error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error: Unable to remove course. Please try again later.';
                header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id);
                exit();
            }

        } else {
            // If 'faculty_id' or 'course_section_id' is missing, set an error message
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Faculty ID or Course Section ID is missing.';
            header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id); // Redirect to a fallback page
            exit();
        }

    } else {
        // If CSRF token is invalid, set an error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: Invalid CSRF token. Please try again.';
        header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id);
        exit();
    }
}
?>