<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);
        $department_name = mysqli_real_escape_string($con, $_POST['department_name']);
        $department_code = mysqli_real_escape_string($con, $_POST['department_code']);
        $department_description = mysqli_real_escape_string($con, $_POST['department_description']);
        $chair_id = mysqli_real_escape_string($con, $_POST['chair_id']);

        // SQL query to update the department
        $query = "UPDATE departments SET 
            department_name = '$department_name', 
            department_code = '$department_code', 
            department_description = '$department_description',
            updated_at = NOW()
            WHERE department_id = '$department_id'";

        // Attempt to execute the query
        if (mysqli_query($con, $query)) {
            // Check if a program chair is assigned
            $currentChairQuery = "SELECT chair_id FROM program_chairs WHERE department_id = '$department_id'";
            $currentChairResult = mysqli_query($con, $currentChairQuery);
            $currentChairId = mysqli_fetch_assoc($currentChairResult)['chair_id'] ?? null;

            // If the chair_id is different, update program chair association
            if (!empty($chair_id) && $chair_id != $currentChairId) {
                // Remove the current department from the chair
                if ($currentChairId) {
                    $clearCurrentChairQuery = "UPDATE program_chairs SET department_id = NULL WHERE chair_id = '$currentChairId'";
                    mysqli_query($con, $clearCurrentChairQuery);
                }

                // Update the new program chair's department association
                $updateChairQuery = "UPDATE program_chairs SET department_id = '$department_id' WHERE chair_id = '$chair_id'";
                if (!mysqli_query($con, $updateChairQuery)) {
                    // If updating the chair fails, log the error
                    error_log("Error updating program chair: " . mysqli_error($con));
                }
            }

            // Set success message in session and redirect
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Department updated successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Log the error and set error message in session
            error_log("Database Error: " . mysqli_error($con));
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating department. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

    } else {
        // If CSRF token doesn't match, set error message and redirect
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
