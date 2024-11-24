<?php
// student_dashboard.php
session_start();
include('databasecon.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch the user's name and email from the session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Student';
$email = $_SESSION['email'];

// For showing welcome only once when dashboard refreshes
if (!isset($_SESSION['welcome_shown'])) {
    $_SESSION['welcome_shown'] = true; // Set flag to true
} else {
    $_SESSION['welcome_shown'] = false; // Flag is already true, don't show the message
}

// Fetch user's profile image from the database (updated column name to profile_image)
$sql = "SELECT profile_image FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($profile_image);
$stmt->fetch();
$stmt->close(); // Close the statement

// The profile_image will automatically default to 'uploads/default_profile.jpg' if it's NULL or empty in the database

// Fetch the courses the user is enrolled in
$sql = "SELECT 
            c.course_name, 
            c.course_code, 
            c.course_description, 
            cs.section
        FROM courses c
        JOIN course_sections cs ON c.course_id = cs.course_id
        JOIN student_courses sc ON cs.course_section_id = sc.course_section_id
        JOIN students s ON sc.student_id = s.student_id
        WHERE s.email = ?"; // Use the student's email to fetch their courses

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);  // Bind the email parameter to the query
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
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
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
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .card h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #800000;
            margin: 15px 0;
        }

        /* Profile Picture Styling */
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #800000;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .profile-pic:hover {
            transform: scale(1.1);
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
<body>

    <!-- Centered Header -->
    <div class="header">
        <h1>Student Dashboard</h1>
        <nav>
            <a style="text-decoration: underline; font-weight: bold;" href="student_dashboard.php">Courses</a>
            <a href="userprofile.php">Profile</a>
            <a href="student_evaluation.php">Evaluate</a>
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
                <!-- Display profile image (it will use default if no custom image exists in the database) -->
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-pic">
                <h2><?php echo htmlspecialchars($name); ?></h2> <!-- Display the student's full name here -->
            </div>

            <!-- Courses Section -->
            <h3>Courses Enrolled To</h3>
            <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <h4><?php echo htmlspecialchars($course['course_name']); ?> (<?php echo htmlspecialchars($course['course_code']); ?>) - Section: <?php echo htmlspecialchars($course['section']); ?></h4>
                    <div class="course-info">
                        <p><?php echo htmlspecialchars($course['course_description']); ?></p>
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
