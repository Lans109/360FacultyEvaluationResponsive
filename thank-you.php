<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['evaluation_complete'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thank You - Faculty Evaluation</title>
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
            text-align: center;
        }
        
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .message {
            margin-bottom: 20px;
            color: #333;
        }
        
        .message p {
            margin-bottom: 10px;
        }
        
        .improvements {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 30px;
        }
        
        .improvement-item {
            color: #1e88e5;
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .card {
                padding: 20px;
            }
            
            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <img src="LPU-LOGO.png" alt="LPU Logo" class="logo">
            <h2>Thank You for Your Feedback!</h2>
            
            <?php if (isset($_SESSION['average_score'])): ?>
            <div class="message">
                <p>Average Rating: <?php echo $_SESSION['average_score']; ?> / 5</p>
            </div>
            <?php endif; ?>
            
            <div class="message">
                <p>We appreciate your valuable feedback!</p>
                <p>Your assessment helps us improve:</p>
            </div>
            
            <div class="improvements">
                <p class="improvement-item">In-Depth Understanding</p>
                <p class="improvement-item">Positive Learning Experience</p>
                <p class="improvement-item">Personalized Support</p>
                <p class="improvement-item">More Efficient Teaching Methods</p>
                <p class="improvement-item">Guarantee Of Reliable Materials</p>
            </div>
            
            <a href="dashboard.php" class="btn" style="display: inline-block; text-decoration: none; margin-top: 20px;">
                Return to Dashboard
            </a>
        </div>
    </div>
</body>
</html>