<?php
// Start the session
session_start();

// Check if the session is empty (session expired) or the user is not an admin (access denied)
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || $_SESSION['acc_type'] !== 'admin') {
    // Check if session is empty
    if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
        // Session expired, set the status and message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Session expired. Please log in again.';
    } else {
        // Access denied for non-admin, set the status and message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Access denied. Admins only.';
    }

    // Set the response status code to 403 Forbidden
    http_response_code(403);

    // Optionally, display a custom error message or a page
    echo "<h1>403 Forbidden</h1>";
    echo "<p>You do not have permission to access this page.</p>";

    // Redirect to the previous page or show an error message
    header("Location: ../../../index.php");
    exit();
}
?>
