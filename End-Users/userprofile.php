<?php
// userprofile.php
session_start();
include('db/databasecon.php');

// Ensure the user is logged in and the user_type exists in the session
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit();
}

// Get user details from the session
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$user_type = $_SESSION['user_type'];

// Initialize variables
$department = "";
$profile_image = "";
$num_courses = 0;

// Determine the SQL query based on the user type
$sql = match ($user_type) {
    'students' => "
        SELECT s.profile_image, COUNT(sc.course_section_id) 
        FROM students s
        LEFT JOIN student_courses sc ON s.student_id = sc.student_id
        WHERE s.email = ? 
        GROUP BY s.profile_image",
    'faculty' => "
        SELECT f.profile_image, d.department_name 
        FROM faculty f
        JOIN faculty_departments fd ON f.faculty_id = fd.faculty_id
        JOIN departments d ON fd.department_id = d.department_id
        WHERE f.email = ?",
    'program_chair' => "
        SELECT pc.profile_image, d.department_name 
        FROM program_chairs pc
        JOIN departments d ON pc.department_id = d.department_id
        WHERE pc.email = ?",
    default => "",
};

// If the query isn't set correctly, stop execution
if (empty($sql)) {
    die("Error: Invalid user type.");
}

// Execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
if ($user_type === 'students') {
    $stmt->bind_result($profile_image, $num_courses);
} else {
    $stmt->bind_result($profile_image, $department);
}
$stmt->fetch();
$stmt->close();

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_ext, $allowed_extensions) && $file['size'] <= 2097152) {
        $new_file_name = uniqid('', true) . '.' . $file_ext;
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_file_name)) {
            $update_sql = match ($user_type) {
                'students' => "UPDATE students SET profile_image = ? WHERE email = ?",
                'faculty' => "UPDATE faculty SET profile_image = ? WHERE email = ?",
                'program_chair' => "UPDATE program_chairs SET profile_image = ? WHERE email = ?",
                default => "",
            };
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $new_file_name, $email);
            $stmt->execute();
            $stmt->close();
            header("Location: userprofile.php");
            exit();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>

<body>
    <div class="header">
        <h1>User Profile</h1>
        <nav>
            <div class="nav-items">
                <a href="userprofile.php"
                    class="<?php echo (basename($_SERVER['PHP_SELF']) == 'userprofile.php') ? 'active' : ''; ?>">Profile</a>
                <?php if ($user_type === 'students'): ?>
                <a href="students/student_dashboard.php"
                    class="<?php echo (basename($_SERVER['PHP_SELF']) == 'student_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <?php elseif ($user_type === 'faculty'): ?>
                <a href="faculty/faculty_dashboard.php"
                    class="<?php echo (basename($_SERVER['PHP_SELF']) == 'faculty_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <?php elseif ($user_type === 'program_chair'): ?>
                <a href="program_chair/program_chair_dashboard.php"
                    class="<?php echo (basename($_SERVER['PHP_SELF']) == 'program_chair_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <?php endif; ?>
                <a href="../logout.php">Logout</a>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="profile-section">
            <div class="profile-card">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-pic">
                <h2><?php echo htmlspecialchars($name); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($user_type); ?></p>
                <?php if ($user_type !== 'students'): ?>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
                <?php else: ?>
                <p><strong>Enrolled Courses:</strong> <?php echo $num_courses; ?></p>
                <?php endif; ?>
                <button onclick="openModal()">Change Profile Picture</button>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form action="userprofile.php" method="post" enctype="multipart/form-data">
                <label for="profile_image">Upload New Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                <button type="submit">Update Image</button>
            </form>
        </div>
    </div>

    <script>
    function openModal() {
        document.getElementById("modal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("modal").style.display = "none";
    }
    </script>
</body>

</html>