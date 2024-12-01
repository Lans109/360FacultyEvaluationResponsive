<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the request method is POST and if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get the form data and sanitize it to prevent SQL injection
        $semester = mysqli_real_escape_string($con, $_POST['semester']);
        $academic_year = mysqli_real_escape_string($con, $_POST['academic_year']);
        $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['end_date']);

        // Validate input to ensure all fields are filled
        if (empty($semester) || empty($academic_year) || empty($start_date) || empty($end_date)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'All fields are required!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate the date format (YYYY-MM-DD)
        $start_date_valid = DateTime::createFromFormat('Y-m-d', $start_date);
        $end_date_valid = DateTime::createFromFormat('Y-m-d', $end_date);

        if (!$start_date_valid || $start_date_valid->format('Y-m-d') !== $start_date) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid start date format!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if (!$end_date_valid || $end_date_valid->format('Y-m-d') !== $end_date) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid end date format!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate that the start date is not in the past
        $today = (new DateTime())->format('Y-m-d');
        if ($start_date < $today) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Start date cannot be in the past!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate that the start date is before the end date
        if ($start_date > $end_date) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Start date cannot be later than the end date!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Validate the academic year format (YYYY-YYYY)
        if (!preg_match("/^\d{4}-\d{4}$/", $academic_year)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid academic year format. Use YYYY-YYYY (e.g., 2024-2025).';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Split academic year into start and end years
        list($start_year, $end_year) = explode('-', $academic_year);
        if ((int)$end_year !== (int)$start_year + 1) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'The academic year must have a one-year gap (e.g., 2024-2025).';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Check for overlapping evaluation periods or incomplete evaluations
        $overlap_check = "SELECT * FROM evaluation_periods 
                          WHERE (('$start_date' BETWEEN start_date AND end_date) 
                              OR ('$end_date' BETWEEN start_date AND end_date) 
                              OR (start_date BETWEEN '$start_date' AND '$end_date') 
                              OR (end_date BETWEEN '$start_date' AND '$end_date')) 
                              AND is_completed = 0";

        $result = mysqli_query($con, $overlap_check);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'There is an active or overlapping evaluation period. Please check the dates.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Insert the new evaluation into the database
        $insert_evaluation = "INSERT INTO evaluation_periods (semester, academic_year, start_date, end_date) 
        VALUES ('$semester', '$academic_year', '$start_date', '$end_date')";

        if (mysqli_query($con, $insert_evaluation)) {
            // Fetch the last inserted ID
            $new_period_id = mysqli_insert_id($con);

            // Mark all previous periods as completed
            $update_evaluation = "UPDATE evaluation_periods 
                SET is_completed = 1 
                WHERE period_id != '$new_period_id' AND is_completed = 0";

            if (mysqli_query($con, $update_evaluation)) {
                // Set session variable for the new period
                $_SESSION['period_id'] = $new_period_id;

                // Success message
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Evaluation added and previous periods marked as completed successfully!';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                // Log error and set error message
                error_log("Database Error: " . mysqli_error($con));
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error: Unable to mark previous periods as completed.';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        } else {
            // Log error and set error message
            error_log("Database Error: " . mysqli_error($con));
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Unable to add evaluation.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        // Invalid CSRF token
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    // Invalid request method
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
