<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the program ID is provided and the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['program_id'])) {
    $program_id = intval($_POST['program_id']); // Sanitize the program ID

    // CSRF token validation
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Unset the CSRF token after validation and use
        unset($_SESSION['csrf_token']);

        // Proceed to delete the program from the database
        $delete_program_query = "DELETE FROM programs WHERE program_id = $program_id";

        if (mysqli_query($con, $delete_program_query)) {
            // If successful, set a success message in the session
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Program deleted successfully!';
            header("Location: programs.php");
            exit();
        } else {
            // If query fails, log the error and set an error message in the session
            error_log("Database Error: " . mysqli_error($con)); // Log the error for debugging
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to delete program. Please try again later.';
            header("Location: programs.php");
            exit();
        }
    } else {
        // If CSRF token is invalid, set an error message and redirect
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: Invalid CSRF token.';
        header("Location: programs.php");
        exit();
    }
} else {
    // If program ID is not provided or request method is not POST, set an error message
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Error: Invalid program ID or request method.';
    header("Location: programs.php");
    exit();
}
?>
