<?php
// userprofile.php
session_start();
require_once('db/databasecon.php');

// Authentication and Authorization Check
function authenticateUser() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_type'])) {
        header("Location: ../index.php");
        exit();
    }
}

// Profile Image Upload Handler
function handleProfileImageUpload($conn, $email, $user_type) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
        $file = $_FILES['profile_image'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file
        if (in_array($file_ext, $allowed_extensions) && $file['size'] <= 2097152) {
            $new_file_name = uniqid('', true) . '.' . $file_ext;
            $upload_dir = 'uploads/';
            
            // Create upload directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_file_name)) {
                // Update profile image in database
                $update_sql = match ($user_type) {
                    'students' => "UPDATE students SET profile_image = ? WHERE email = ?",
                    'faculty' => "UPDATE faculty SET profile_image = ? WHERE email = ?",
                    'program_chair' => "UPDATE program_chairs SET profile_image = ? WHERE email = ?",
                    default => "",
                };

                if (!empty($update_sql)) {
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ss", $new_file_name, $email);
                    $stmt->execute();
                    $stmt->close();
                }

                header("Location: userprofile.php");
                exit();
            }
        }
    }
}

// Fetch User Profile Details
function fetchUserProfile($conn, $email, $user_type) {
    $profile_data = [
        'profile_image' => 'default-avatar.png',
        'department' => '',
        'num_courses' => 0
    ];

    $sql = match ($user_type) {
        'students' => "
            SELECT s.profile_image, COUNT(sc.course_section_id) as course_count
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

    if (!empty($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($user_type === 'students') {
            $stmt->bind_result($profile_data['profile_image'], $profile_data['num_courses']);
        } else {
            $stmt->bind_result($profile_data['profile_image'], $profile_data['department']);
        }
        $stmt->fetch();
        $stmt->close();
    }

    return $profile_data;
}

// Main Execution
authenticateUser();

// Get user details from the session
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$user_type = $_SESSION['user_type'];

// Handle profile image upload
handleProfileImageUpload($conn, $email, $user_type);

// Fetch user profile details
$profile = fetchUserProfile($conn, $email, $user_type);

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
                   class="<?php echo (basename($_SERVER['PHP_SELF']) == 'userprofile.php') ? 'active' : ''; ?>">
                    Profile
                </a>
                <?php 
                $dashboard_links = [
                    'students' => 'students/student_dashboard.php',
                    'faculty' => 'faculty/faculty_dashboard.php',
                    'program_chair' => 'program_chair/program_chair_dashboard.php'
                ];
                
                if (isset($dashboard_links[$user_type])): ?>
                <a href="<?php echo $dashboard_links[$user_type]; ?>" 
                   class="<?php echo (basename($_SERVER['PHP_SELF']) == basename($dashboard_links[$user_type])) ? 'active' : ''; ?>">
                    Dashboard
                </a>
                <?php endif; ?>
                <a href="../logout.php">Logout</a>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="profile-section">
            <div class="card profile-card">
                <img src="uploads/<?php echo htmlspecialchars($profile['profile_image'] ?? 'default-avatar.png'); ?>" 
                     alt="Profile Picture" class="profile-pic">
                
                <h2><?php echo htmlspecialchars($name); ?></h2>
                
                <div class="profile-details">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($user_type); ?></p>
                    
                    <?php if ($user_type !== 'students'): ?>
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($profile['department'] ?? 'N/A'); ?></p>
                    <?php else: ?>
                        <p><strong>Enrolled Courses:</strong> <?php echo $profile['num_courses']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="profile-actions">
                    <button onclick="openModal()">Change Profile Picture</button>
                </div>
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
        document.getElementById("modal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("modal").style.display = "none";
    }
    </script>
</body>
</html>