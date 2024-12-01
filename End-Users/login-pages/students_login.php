<?php
#student login page
?>
<!DOCTYPE html>
<html>

<head>
    <link rel='stylesheet' href="/360FacultyEvaluationSystem/End-Users/Styles/index-style.css">
    <title>Login - Faculty Evaluation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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
    </style>
        <nav class="topnav">
        </nav>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="login-box">
                <div class="logo">
                    <img src="/360FacultyEvaluationSystem/End-Users/LPU-LOGO.png" alt="LPU Logo">
                </div>
                <div class="title-area">
                        <h1>Student Login</h1>
                    </div>
                <!-- Display error messages -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message show"><?= htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="process_login.php">
                    <input type="hidden" name="user_type" value="students"> <!-- Hidden field to specify user type -->
                    
                    <div class="user-box">
                        <input type="email" name="email" class="form-control" required>
                        <label for="username">Email:</label>
                    </div>
                    <div class="user-box">
                        <input type="password" name="password" class="form-control" required>
                        <label for="password">Password:</label>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script>

    </script>
</body>
</html>