<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login_option.php');
    exit();
}

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie, if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to the login page (or other pages as necessary)
header('Location: login_option.php');
exit();
?>
