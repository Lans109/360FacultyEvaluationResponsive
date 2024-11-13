<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Faculty Evaluation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
            color: #333;
        }

        /* Main Container */
        .container {
            min-height: 100vh;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Profile Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
        }

        /* Profile Section */
        .profile {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-picture {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
            border: 2px solid #800000;
            overflow: hidden;
        }

        .profile-picture img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .profile-info {
            flex-grow: 1;
            padding: 10px;
        }

        .name {
            font-size: 24px;
            color: #800000;
            font-weight: bold;
        }

        .course-info {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Enrolled Courses Section */
        .enrolled-courses {
            margin-top: 20px;
        }

        .enrolled-courses h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .course-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .course-item {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            transition: background-color 0.3s;
        }

        .course-item:hover {
            background: #f1f1f1;
        }

        .course-code {
            font-weight: bold;
            color: #800000;
        }

        .course-name,
        .course-duration,
        .course-lessons {
            font-size: 14px;
            color: #666;
        }

        /* Back Button */
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #800000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
        }

        .btn-back:hover {
            background: #600000;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .name {
                font-size: 20px;
            }

            .course-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <!-- Profile Section -->
            <div class="profile">
                <div class="profile-picture">
                    <img src="Sample.jpeg" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <h2 class="name">
                        <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?>
                    </h2>
                    <div class="course-info">
                        <p>Bachelor of Computer Science, 3rd Year</p>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses Section -->
            <div class="enrolled-courses">
                <h3>Enrolled Courses</h3>
                <div class="course-list">
                    <?php
                    if (isset($_SESSION['courses']) && is_array($_SESSION['courses'])):
                        foreach ($_SESSION['courses'] as $course):
                    ?>
                            <div class="course-item">
                                <p class="course-code"><?php echo htmlspecialchars($course['code']); ?></p>
                                <p><?php echo htmlspecialchars($course['duration']); ?> Duration</p>
                                <p><?php echo htmlspecialchars($course['lessons']); ?> Lessons</p>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <p>No courses found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Back to Dashboard Button -->
            <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>