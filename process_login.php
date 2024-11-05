<?php
// process_login.php
session_start();
require_once 'config.php';

if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: login.php?logged_out=1');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (isset($valid_users[$email]) && $valid_users[$email]['password'] === $password) {
        // Start a new session
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $valid_users[$email]['id'];
        $_SESSION['name'] = $valid_users[$email]['name'];
        $_SESSION['profile_pic'] = $valid_users[$email]['profile_pic'];
        $_SESSION['courses'] = $valid_users[$email]['courses'];
        $_SESSION['login_time'] = time();
        
        header('Location: dashboard.php');
        exit();
    }
    
    header('Location: login.php?error=1');
    exit();
}

header('Location: login.php');
exit();