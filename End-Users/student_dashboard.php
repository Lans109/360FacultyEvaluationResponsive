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
    <style>
    /* Reset Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", Arial, sans-serif;
        background: linear-gradient(135deg, #7D0006, #D3D3D3);
        color: #000;
        line-height: 1.6;
    }

    /* Header Section */
    .header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 1.5rem 0;
        text-align: center;
        color: var(--white);
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: var(--shadow-small);
    }

    .header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        padding: 1rem;
        background-color: #7D0006;
        color: var(--white);
        text-align: center;
        border-radius: 0;
        box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .header nav {
        margin-top: 0.5rem;
    }

    /* Header Navigation Links */
    .header nav a {
        color: var(--white);
        text-decoration: none;
        margin: 0 1rem;
        font-size: 1.1rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        background-color: #7D0006;
        border-radius: 8px;
        display: inline-block;
        transition: var(--transition-speed);
        box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .header nav a:hover {
        color: #000;
        text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
        box-shadow: 3px 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Container */
    .container {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 1rem;
        background: var(--secondary-color);
        border-radius: 12px;
        box-shadow: var(--shadow-large);
    }

    /* Profile Card */
    .card {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        text-align: center;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.25);
        border: 3px solid rgba(125, 0, 6, 0.1);
        transition: var(--transition-speed);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.3);
        border: 3px solid rgba(125, 0, 6, 0.3);
    }

    .card h1 {
        color: #000;
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
    }

    .card p {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: #000;
    }

    .card hr {
        border: 0;
        height: 1px;
        background: var(--primary-light);
        margin: 1.5rem 0;
    }

    /* Profile Picture */
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid var(--primary-light);
        box-shadow: var(--shadow-small);
        transition: var(--transition-speed);
        object-fit: cover;
        cursor: pointer;
    }

    .profile-pic:hover {
        transform: scale(1.1);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Button Styles */
    button {
        background: var(--primary-color);
        color: #000;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition-speed);
        box-shadow: var(--shadow-small);
    }

    button:hover {
        background: var(--primary-light);
        box-shadow: var(--shadow-large);
    }

    /* Modal Container */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    /* Modal Content */
    .modal-content {
        background: #ffffff;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-out;
    }

    /* Modal Header */
    .modal-header {
        font-size: 1.8rem;
        color: #7D0006;
        text-align: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid #ddd;
        padding-bottom: 1rem;
    }

    /* Modal Body */
    .modal-body {
        font-size: 1.1rem;
        text-align: center;
        color: #000;
        margin-bottom: 1.5rem;
    }

    .modal-body input[type="file"] {
        display: block;
        margin: 1rem auto;
        padding: 0.5rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 90%;
        max-width: 300px;
    }

    /* Buttons in Modal */
    .btn-change,
    .btn-cancel {
        background: #e0e0e0;
        color: #000;
        border: none;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0.5rem;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-change:hover {
        background: #7D0006;
        color: #fff;
    }

    .btn-cancel:hover {
        background: #999;
        color: #fff;
    }

    /* Modal Footer */
    .modal-footer {
        text-align: center;
        margin-top: 1rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .modal-content {
            width: 95%;
            padding: 1.5rem;
        }

        .modal-header {
            font-size: 1.5rem;
        }

        .modal-body input[type="file"] {
            width: 100%;
        }

        .btn-change,
        .btn-cancel {
            font-size: 0.9rem;
            padding: 0.6rem 1rem;
        }
    }
    </style>
</head>

<body>

    <!-- Centered Header -->
    <div class="header">
        <h1>Student Dashboard</h1>
        <nav>
            <a style="text-decoration: underline; font-weight: bold;" href="student_dashboard.php">Courses</a>
            <a href="userprofile.php">Profile</a>
            <a href="student_evaluation.php">Evaluate</a>
            <a href="/360FacultyEvaluationSystem/logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
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
                <h2><?php echo htmlspecialchars($name); ?></h2>
            </div>

            <!-- Courses Section -->
            <h3 style="margin-top: 30px; margin-bottom: 20px;">Courses Enrolled To</h3>
            <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <h4><?php echo htmlspecialchars($course['course_name']); ?>
                    (<?php echo htmlspecialchars($course['course_code']); ?>) - Section:
                    <?php echo htmlspecialchars($course['section']); ?></h4>
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