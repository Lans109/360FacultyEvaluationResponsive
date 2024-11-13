<?php
// save-evaluation.php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Redirect back to the faculty list
    header('Location: Faculty-Evaluation-list.php');
    exit();
}
?>