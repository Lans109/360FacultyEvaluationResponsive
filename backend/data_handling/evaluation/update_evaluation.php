<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    // Get the status and period_id from the POST request
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    $period_id = mysqli_real_escape_string($con, $_POST['period_id']);

    // Update the status in the database
    $update_query = "UPDATE evaluation_periods SET status='$new_status' WHERE period_id='$period_id'";
    
    if (mysqli_query($con, $update_query)) {
        // Redirect back to evaluation page after successful update
        header("Location: " . $_SERVER['HTTP_REFERER']); 
        exit;
    } else {
        echo '<div class="alert alert-danger">Error updating status: ' . mysqli_error($con) . '</div>';
    }
} else {
    // Redirect back to evaluation page if accessed directly
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;
}
?>
