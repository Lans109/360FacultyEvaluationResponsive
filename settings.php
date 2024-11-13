<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Redirect to profile page
header('Location: profile.php');
exit();
?>