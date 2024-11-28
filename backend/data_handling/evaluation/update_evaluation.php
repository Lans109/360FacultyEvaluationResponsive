<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get and sanitize the inputs to prevent SQL injection
        $period_id = mysqli_real_escape_string($con, $_POST['period_id']);
        $new_status = mysqli_real_escape_string($con, $_POST['status']);
        $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['end_date']);

        // SQL query to update the evaluation period's details
        $update_query = "UPDATE evaluation_periods 
                         SET 
                             status = '$new_status', 
                             start_date = '$start_date', 
                             end_date = '$end_date' 
                         WHERE 
                             period_id = '$period_id'";

        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            // Unset the old period_id
            unset($_SESSION['period_id']);
            
            // Set the new period_id in the session
            $_SESSION['period_id'] = $period_id;

            // If successful, set session variables for success message
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Evaluation period updated successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If query fails, log the error and set session variables for error message
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating evaluation period. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

} else {
    // If no POST request is made, set session variables for error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
