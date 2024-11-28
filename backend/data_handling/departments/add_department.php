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

        // Sanitize input to prevent SQL injection
        $department_name = mysqli_real_escape_string($con, $_POST['department_name']);
        $department_code = mysqli_real_escape_string($con, $_POST['department_code']);
        $department_description = mysqli_real_escape_string($con, $_POST['department_description']);
        $chair_id = mysqli_real_escape_string($con, $_POST['chair_id']);

        // Validate input to ensure all fields are filled
        if (empty($department_name) || empty($department_code) || empty($department_description)) {
            // Set error message in session and redirect back
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Insert department into the database
        $insert_department = "INSERT INTO departments (department_name, department_code, department_description) 
                              VALUES ('$department_name', '$department_code', '$department_description')";

        if (mysqli_query($con, $insert_department)) {
            $department_id = mysqli_insert_id($con);

            // Assign program chair if selected
            if (!empty($chair_id)) {
                // Update the program chair with the new department_id
                $assign_chair = "UPDATE program_chairs SET department_id = $department_id WHERE chair_id = $chair_id";
                mysqli_query($con, $assign_chair);
            }

            // Set success message in session and redirect back
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Department added successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Log the error and set an error message in session
            error_log("Database Error: " . mysqli_error($con)); // Log the error
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add department. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        // If CSRF token is invalid, set error message and redirect back
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
