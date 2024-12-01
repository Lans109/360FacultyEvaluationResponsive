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

        // Get form data and sanitize it to prevent SQL injection
        $period_id = mysqli_real_escape_string($con, $_POST['period_id']);
        $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['end_date']);
        $status = mysqli_real_escape_string($con, $_POST['evaluation_status']);
        
        // Scoring percentages
        $student_scoring = mysqli_real_escape_string($con, $_POST['student_scoring']);
        $self_scoring = mysqli_real_escape_string($con, $_POST['self_scoring']);
        $peer_scoring = mysqli_real_escape_string($con, $_POST['peer_scoring']);
        $chair_scoring = mysqli_real_escape_string($con, $_POST['chair_scoring']);

        // Calculate the total of all scoring percentages
        $total_scoring = $student_scoring + $self_scoring + $peer_scoring + $chair_scoring;

        // Validate if the total scoring exceeds 100
        if ($total_scoring > 100) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Total scoring cannot exceed 100%. Please adjust the individual scoring values.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate if the total scoring is less than 100
        if ($total_scoring < 100) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Total scoring must be 100%. Please adjust the individual scoring values.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Get the current date
        $current_date = date('Y-m-d');

        // Validate if the end date is before today's date
        if ($end_date < $current_date) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'End date cannot be before today. Please select a valid end date.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate if trying to set status to "active" outside the start and end date range
        if ($status === 'active' && ($current_date < $start_date || $current_date > $end_date)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Cannot set status to active. The current date must be within the start and end date range.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // SQL query to update the evaluation period with the new values
        $update_query = "UPDATE evaluation_periods SET 
            start_date = '$start_date', 
            end_date = '$end_date', 
            status = '$status', 
            student_scoring = '$student_scoring', 
            self_scoring = '$self_scoring', 
            peer_scoring = '$peer_scoring', 
            chair_scoring = '$chair_scoring' 
            WHERE period_id = '$period_id'";

        // Attempt to execute the update query
        if (mysqli_query($con, $update_query)) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Evaluation updated successfully!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            error_log("Database Error: " . mysqli_error($con));
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating evaluation. Please try again later.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

?>
