<?php
// evaluate.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="styles.css">
    <title>Evaluate - Faculty Evaluation</title>
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #800000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .time-estimate {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <img src="LPU-LOGO.png" alt="LPU Logo" class="logo">
            <h2>Faculty Evaluation</h2>

            <div class="info-box">
                <p>Your Feedback Shapes the Future of Teaching at LPU-C</p>
                <p>Participate by Evaluating Experiences at LPU-C</p>
            </div>

            <a href="evaluate-faculty.php" class="btn">Click here</a>

            <p class="time-estimate">Estimated time: 5 minutes</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes to elements
            document.body.classList.add('fade-in');

            // Add loading animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('btn-loading');
                });
            });

            // Add animation to form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    this.classList.add('form-submitting');
                });
            });
        });
    </script>
</body>
</html>