<?php
session_start();
include('../db/databasecon.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Fetch the user's name and email from the session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Faculty Member'; // Default value if not set
$email = $_SESSION['email'];

// For showing welcome only once when dashboard refreshes
if (!isset($_SESSION['welcome_shown'])) {
    $_SESSION['welcome_shown'] = true; // Set flag to true
} else {
    $_SESSION['welcome_shown'] = false; // Flag is already true, don't show the message
}

// Fetch the profile image for the faculty from the database
$sql = "SELECT profile_image FROM faculty WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($profile_image);
$stmt->fetch();
$stmt->close(); // Close the statement

// Fetch the courses the faculty handles
$sql = "SELECT 
            c.course_name, 
            c.course_code, 
            c.course_description, 
            cs.section
        FROM courses c
        JOIN course_sections cs ON c.course_id = cs.course_id
        JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
        JOIN faculty f ON fc.faculty_id = f.faculty_id
        WHERE f.email = ?"; // Use the faculty's email to fetch their courses

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($course_name, $course_code, $course_description, $section);

// Fetch courses into an array
$courses = [];
while ($stmt->fetch()) {
    $courses[] = [
        'course_name' => $course_name,
        'course_code' => $course_code,
        'course_description' => $course_description,
        'section' => $section
    ];
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="styles.css">
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
        <h1>Faculty Dashboard</h1>
        <nav>
            <a style="text-decoration: underline; font-weight: bold;" href="faculty_dashboard.php">Courses Handled</a>
            <a href="userprofile.php">Profile</a>
            <a href="faculty_evaluation.php">Evaluate</a>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </nav>
    </div>

    <div class="container">
        <!-- Welcome Message -->
        <?php if ($_SESSION['welcome_shown']): ?>
        <div class="welcome-message" id="welcome-message">
            Welcome back, <?php echo htmlspecialchars($name); ?>!
            <button class="close-btn" onclick="closeWelcomeMessage()">X</button>
        </div>
    <?php endif; ?>

        <!-- Profile Section -->
        <div class="card">
            <div class="profile">
                <!-- Display Profile Picture -->
                <img src="<?php echo isset($profile_image) && !empty($profile_image) ? $profile_image : 'default_profile_pic.jpg'; ?>" alt="Profile Picture" class="profile-pic">
                <h2><?php echo htmlspecialchars($name); ?></h2>
            </div>

            <!-- Courses Section -->
            <h3>Course Sections You Handle</h3>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h4><?php echo htmlspecialchars($course['course_name']); ?> (<?php echo htmlspecialchars($course['course_code']); ?>) - Section: <?php echo htmlspecialchars($course['section']); ?></h4>
                        <div class="course-info">
                            <span>Description: <?php echo "<br>". htmlspecialchars($course['course_description']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You don't handle any courses.</p>
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
