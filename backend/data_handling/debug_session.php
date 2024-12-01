<?php
// Start the session
session_start();

// Check if there are session variables
if (!empty($_SESSION)) {
    echo "<pre>";
    print_r($_SESSION); // Displays all session data
    echo "</pre>";
} else {
    echo "No session data is available.";
}
?>