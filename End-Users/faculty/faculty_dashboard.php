<?php
// faculty_dashboard.php
session_start();
include('../db/databasecon.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../index.php");
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
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <!-- Centered Header -->
    <div class="header">
        <div class="nav-title">
            <h1>
                Faculty Dashboard
            </h1>
        </div>
        <?php include 'faculty_navbar.php' ?>
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
                <img src="../<?php echo isset($profile_image) && !empty($profile_image) ? $profile_image : 'default_profile_pic.jpg'; ?>" alt="../<?php echo htmlspecialchars($profile_image); ?>" class="profile-pic">

                <h2><?php echo htmlspecialchars($name); ?></h2>
            </div>

            <!-- Courses Section -->
            <h3>Course Sections You Handle</h3>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h4><?php echo htmlspecialchars($course['course_name']); ?>
                            (<?php echo htmlspecialchars($course['course_code']); ?>) - Section:
                            <?php echo htmlspecialchars($course['section']); ?>
                        </h4>
                        <div class="course-info">
                            <span>Description: <?php echo "<br>" . htmlspecialchars($course['course_description']); ?></span>
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