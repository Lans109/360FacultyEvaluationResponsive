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
            background: #f5f5f5;
        }

        .header {
            text-align: center;
            background: #800000;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .header nav {
            margin-top: 10px;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
        }

        .header nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .welcome-message {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1.2rem;
            position: relative;
        }

        .welcome-message .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: #800000;
            cursor: pointer;
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
            border: 3px solid #800000;
        }

        .course-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .course-card h4 {
            font-size: 1.2rem;
            color: #800000;
            margin-bottom: 5px;
        }

        .course-info {
            font-size: 0.9rem;
            color: #666;
            margin-top: 10px;
        }

        .course-info span {
            display: block;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-pic {
                margin-bottom: 15px;
            }

            .header nav {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .course-card {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Centered Header -->
    <div class="header">
        <h1>Dashboard</h1>
        <nav>
            <a href="profile.php">Profile</a>
            <a href="evaluate.php">Evaluate</a>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </nav>
    </div>

    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome-message" id="welcome-message">
            Welcome back, <?php echo $name; ?>!
            <button class="close-btn" onclick="closeWelcomeMessage()">X</button>
        </div>

        <!-- Profile Section -->
        <div class="card">
            <div class="profile">
                <img src="Sample.jpeg" alt="Profile Picture" class="profile-pic">
                <h2><?php echo $name; ?></h2>
            </div>

            <!-- Courses Section -->
            <h3>Your Courses</h3>

            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h4><?php echo htmlspecialchars($course['code']); ?></h4>
                        <div class="course-info">
                            <span>Duration: <?php echo htmlspecialchars($course['duration']); ?></span>
                            <span>Lessons: <?php echo htmlspecialchars($course['lessons']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function closeWelcomeMessage() {
            document.getElementById('welcome-message').style.display = 'none';
        }
    </script>
</body>
</html>