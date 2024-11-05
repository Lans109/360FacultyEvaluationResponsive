<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize evaluation session if not exists
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 1;
    $_SESSION['answers'] = [];
    $_SESSION['faculty_id'] = $_POST['faculty_id'] ?? null;
    
    if (!$_SESSION['faculty_id']) {
        header('Location: evaluate-faculty.php');
        exit();
    }
}

// Questions array
$questions = [
    1 => "Did you feel that the instructor created an environment comfortable for your practical classes direction?",
    2 => "How effective was the instructor in explaining complex concepts?",
    3 => "How well did the instructor maintain student engagement during classes?",
    4 => "How would you rate the instructor's preparedness for each class?",
    5 => "How accessible was the instructor for questions outside of class time?"
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Form - Faculty Evaluation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .logo {
            width: 100px;
            display: block;
            margin: 0 auto 20px;
        }
        
        h2, .question-number {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .question {
            margin-bottom: 30px;
        }
        
        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .option-btn {
            width: 100%;
            padding: 12px;
            background: #ffebee;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
            font-size: 16px;
        }
        
        .option-btn:hover {
            background: #ffcdd2;
        }
        
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <img src="LPU-LOGO.png" alt="LPU Logo" class="logo">
            <h2>Evaluation Form</h2>
            <p class="question-number">Question <?php echo $_SESSION['current_question']; ?> of 5</p>
            
            <form action="process_evaluation.php" method="POST">
                <div class="question">
                    <p><?php echo htmlspecialchars($questions[$_SESSION['current_question']]); ?></p>
                    
                    <div class="options">
                        <button type="submit" name="answer" value="5" class="option-btn">Very Satisfied</button>
                        <button type="submit" name="answer" value="4" class="option-btn">Satisfied</button>
                        <button type="submit" name="answer" value="3" class="option-btn">Neutral</button>
                        <button type="submit" name="answer" value="2" class="option-btn">Dissatisfied</button>
                        <button type="submit" name="answer" value="1" class="option-btn">Very Dissatisfied</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>