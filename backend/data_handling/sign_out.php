<?php
include_once "../../config.php";

session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or home page
header("Location: " . SITE_URL . "/index.php"); // Adjust the URL as needed
exit();
?>
