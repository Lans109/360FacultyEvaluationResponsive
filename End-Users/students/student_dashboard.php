<?php
// student_dashboard.php
session_start();
include('../db/databasecon.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../index.php");
    exit();
}

// Fetch user's data from session
$name = $_SESSION['name'] ?? 'Student';
$email = $_SESSION['email'];

// Display welcome message only once
$showWelcome = !isset($_SESSION['welcome_shown']);
$_SESSION['welcome_shown'] = true;

// Fetch user's profile image
$sql = "SELECT profile_image FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($profile_image);
$stmt->fetch();
$stmt->close();

// Fetch courses
$sql = "SELECT 
            c.course_name, 
            c.course_code, 
            c.course_description, 
            cs.section
        FROM courses c
        JOIN course_sections cs ON c.course_id = cs.course_id
        JOIN student_courses sc ON cs.course_section_id = sc.course_section_id
        JOIN students s ON sc.student_id = s.student_id
        WHERE s.email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($course_name, $course_code, $course_description, $section);

$courses = [];
while ($stmt->fetch()) {
    $courses[] = [
        'course_name' => $course_name,
        'course_code' => $course_code,
        'course_description' => $course_description,
        'section' => $section,
    ];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles.css">
    <title>Student Dashboard</title>
</head>
<body>
    <header class="header">
        <h1>Student Dashboard</h1>
        <?php include 'student_navbar.php'; ?>
    </header>

    <main class="container">
        <?php if ($showWelcome): ?>
            <div id="welcome-message" class="welcome-message">
                Welcome back, <?php echo htmlspecialchars($name); ?>!
                <button class="close-btn" onclick="closeWelcomeMessage()">X</button>
            </div>
        <?php endif; ?>

        <!-- Profile Section -->
        <div class="card">

        <section class="courses">
            <h3>Your Courses</h3>
            <div class="courses-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($course['course_code']); ?></h3>
                        <p><?php echo htmlspecialchars($course['course_name']); ?></p>
                        <p>Section: <?php echo htmlspecialchars($course['section']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script>
        function closeWelcomeMessage() {
            document.getElementById('welcome-message').style.display = 'none';
        }
    </script>
</body>
</html>