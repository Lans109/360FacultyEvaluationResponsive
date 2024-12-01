<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is GET and the necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['faculty_id']) && isset($_GET['course_section_id'])) {

    // Get the faculty ID and course section ID from the GET request
    $faculty_id = $_GET['faculty_id'];
    $course_section_id = $_GET['course_section_id'];

    // Validate faculty_id and course_section_id
    if (!empty($faculty_id) && !empty($course_section_id)) {

        // Ensure CSRF token validation to prevent cross-site request forgery attacks
        if (isset($_SESSION['csrf_token']) && isset($_GET['csrf_token']) && $_GET['csrf_token'] === $_SESSION['csrf_token']) {

            // Unset the CSRF token after successful validation
            unset($_SESSION['csrf_token']);

            // Sanitize the course_section_id for security
            $faculty_id = mysqli_real_escape_string($con, $faculty_id);
            $course_section_id = mysqli_real_escape_string($con, $course_section_id);

            // Prepare the SQL query to delete from faculty_courses
            $delete_query = "
                DELETE FROM faculty_courses 
                WHERE faculty_id = '$faculty_id' AND course_section_id = '$course_section_id'";

            // Execute the delete query
            if (mysqli_query($con, $delete_query)) {
                // If successful, set a success message in the session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Course removed successfully!';

                // Redirect to the faculty profile page
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
            // If CSRF token is invalid, set an error message and redirect
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Invalid CSRF token. Please try again.';
            header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id);
            exit();
        }

    } else {
        // If faculty_id or course_section_id is missing, set an error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: Faculty ID or Course Section ID is missing.';
        header("Location: view_faculty_profile.php?faculty_id=" . $faculty_id);
        exit();
    }

} else {
    // If request method is not GET or required parameters are missing, set an error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method or missing parameters.';
    header("Location: faculty.php");
    exit();
}
?>