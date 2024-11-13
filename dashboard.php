<?php
// dashboard.php
session_start();

// Redirect to login if the session is not set
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fallbacks for session variables if not set
$name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest';
$courses = isset($_SESSION['courses']) ? $_SESSION['courses'] : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Faculty Evaluation</title>
    <style>
        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .nav {
            background: #800000;
            color: white;
            padding: 15px 20px;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .course-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .course-info {
            display: flex;
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }

        .course-info span {
            margin-right: 20px;
        }

        .welcome-message {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                text-align: center;
            }

            .container {
                padding: 10px;
            }

            .profile-pic {
                width: 80px;
                height: 80px;
            }

            .course-card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>

    <nav class="nav">
        <div class="nav-content">
            <h1>Dashboard</h1>
            <div>
                <a href="profile.php">Profile</a>
                <a href="evaluate.php">Evaluate</a>
                <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-message">
            Welcome back, <?php echo $name; ?>!
        </div>

        <div class="card">
            <div class="profile">
                <img src="Sample.jpeg" alt="Profile Picture" class="profile-pic">
                <h2><?php echo $name; ?></h2>
            </div>

            <h3>Your Courses</h3>

            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h4><?php echo htmlspecialchars($course['code']); ?></h4>
                        <div class="course-info">
                            <span><?php echo htmlspecialchars($course['duration']); ?></span>
                            <span><?php echo htmlspecialchars($course['lessons']); ?> Lessons</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>
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