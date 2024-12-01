<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and the necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['program_id']) && isset($_POST['course_id'])) {

    // Get the program_id and course_id from the POST request
    $program_id = $_POST['program_id'];
    $course_id = $_POST['course_id'];

    // Validate program_id and course_id
    if (!empty($program_id) && !empty($course_id)) {

        // Ensure CSRF token validation to prevent cross-site request forgery attacks
        if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

            // Unset the CSRF token after successful validation
            unset($_SESSION['csrf_token']);

            // Sanitize the program_id and course_id for security
            $program_id = mysqli_real_escape_string($con, $program_id);
            $course_id = mysqli_real_escape_string($con, $course_id);

            // Prepare the delete query to remove the course from the program
            $delete_query = "
                DELETE FROM program_courses 
                WHERE program_id = '$program_id' AND course_id = '$course_id'";

            // Execute the delete query
            if (mysqli_query($con, $delete_query)) {
                // If successful, set a success message in the session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Course removed successfully!';

                // Redirect to the view_program_courses page with the program_id
                header("Location: view_program_courses.php?program_id=$program_id");
                exit();
            } else {
                // If query fails, log the error and set an error message in the session
                error_log("Database " . mysqli_error($con)); // Log the error for debugging
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Unable to remove course. Please try again later.';
                header("Location: view_program_courses.php?program_id=$program_id");
                exit();
            }
        } else {
            // If CSRF token is invalid, set an error message and redirect
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
            header("Location: view_program_courses.php?program_id=$program_id");
            exit();
        }

    } else {
        // If program_id or course_id is missing, set an error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Program ID or Course ID is missing.';
        header("Location: view_program_courses.php?program_id=$program_id");
        exit();
    }

} else {
    // If request method is not POST or required parameters are missing, set an error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method or missing parameters.';
    header("Location: view_program_courses.php?program_id=$program_id");
    exit();
}
?>
