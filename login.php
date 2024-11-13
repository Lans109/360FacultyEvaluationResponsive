<?php
// login.php
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="styles.css">
    <title>Login - Faculty Evaluation</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #600000;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #ffe6e6;
            border-radius: 4px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #d4edda;
            border-radius: 4px;
            display: none;
        }

        .success-message.show {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <img src="./assets/icons/LPU-LOGO.png" alt="LPU Logo">
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message show">
                    Invalid email or password
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logged_out'])): ?>
                <div class="success-message show">
                    You have been successfully logged out
                </div>
            <?php endif; ?>

            <form method="POST" action="process_login.php">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password"
                        class="form-control" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
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