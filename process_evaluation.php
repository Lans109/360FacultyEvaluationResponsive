<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_question'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $_SESSION['answers'][] = (int)$_POST['answer'];
    $_SESSION['current_question']++;
    
    if ($_SESSION['current_question'] > 5) {
        // Calculate average score
        $average = array_sum($_SESSION['answers']) / count($_SESSION['answers']);
        $_SESSION['evaluation_complete'] = true;
        $_SESSION['average_score'] = round($average, 2);
        
        // Clean up session
        unset($_SESSION['current_question']);
        unset($_SESSION['answers']);
        unset($_SESSION['faculty_id']);
        
        header('Location: thank-you.php');
        exit();
    }
    
    header('Location: evaluate-form.php');
    exit();
}

header('Location: evaluate-faculty.php');
exit();