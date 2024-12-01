<?php
// Start the session
session_start();

// Check if the session is empty (session expired) or the user is not an admin (access denied)
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_key']) || $_SESSION['account_type'] !== 'admin') {
    // Check if session is empty
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_key']) || !isset($_SESSION['account_type'])) {
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
