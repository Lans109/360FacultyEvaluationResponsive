<?php
// userprofile.php
session_start();
require_once('db/databasecon.php');

// Ensure the user is logged in and the user_type exists in the session
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit();
}

// Get the user's email, name, and user type from the session
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$user_type = $_SESSION['user_type'];  // This should now be set after login

// Initialize variables
$department = "";// to get department name
$profile_image = ""; //Fr profile picture
$num_courses = 0; //Course count to display


// Initialize the SQL query variable
$sql = "";

// Check the user type and set the query accordingly
if ($user_type == 'students') {
    // Query to get the profile picture for students (no department for students)
    $sql = "
        SELECT s.profile_image, COUNT(sc.course_section_id) 
        FROM students s
        LEFT JOIN student_courses sc ON s.student_id = sc.student_id
        LEFT JOIN course_sections cs ON sc.course_section_id = cs.course_section_id
        WHERE s.email = ?
        GROUP BY s.profile_image";
} elseif ($user_type == 'faculty') {
    // For faculty, we join `faculty_department` to get the department name
        $sql = "
        SELECT f.profile_image, d.department_name 
        FROM faculty f
        JOIN departments d ON f.department_id = d.department_id
        WHERE f.email = ?";
} elseif ($user_type == 'program_chair') {
    // For program chairs, we directly join the `departments` table
    $sql = "
        SELECT pc.profile_image, d.department_name 
        FROM program_chairs pc
        JOIN departments d ON pc.department_id = d.department_id
        WHERE pc.email = ?";
}

// Check if $sql was set correctly
if ($sql == "") {
    die("Error: SQL query is empty. User type might not be set correctly.");
}

// Execute the query
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error: SQL preparation failed. ' . $conn->error); 
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Bind the result based on the number of columns
if ($user_type == 'students') {
    $stmt->bind_result($profile_image, $num_courses); // Returns profile ad course count for student
} else {
    $stmt->bind_result($profile_image, $department); // Both profile_image and department_name are returned for faculty and program chairs
}

$stmt->fetch();
$stmt->close();

// Handle profile picture update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    // Validate file upload
    $file = $_FILES['profile_image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // File extension validation
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_ext, $allowed_extensions)) {
        $_SESSION['profile_update_error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        header("Location: userprofile.php");  // Redirect to avoid form resubmission
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
    <script>
        // Display success or error message in a popup
        window.onload = function() {
            <?php if (isset($_SESSION['profile_update_success'])): ?>
                alert("<?php echo $_SESSION['profile_update_success']; ?>");
                <?php unset($_SESSION['profile_update_success']); ?>
            <?php elseif (isset($_SESSION['profile_update_error'])): ?>
                alert("<?php echo $_SESSION['profile_update_error']; ?>");
                <?php unset($_SESSION['profile_update_error']); ?>
            <?php endif; ?>
        };
        // Open the modal when the user clicks on the profile image
        function openModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        // Close the modal when the user clicks on "Cancel"
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        // Close the modal if the user clicks anywhere outside the modal
        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }; 
    </script>
</head>
<body>
    <div class="header">
        <h1>User Profile</h1>
        <nav>

            <nav>
                <div class="nav-items">
                    <?php
                    // Get the current script name
                    $current_page = basename($_SERVER['PHP_SELF']);

                    // Dynamically generate navigation links based on user type
                    if ($user_type == 'students') {
                        echo '<a href="students/student_dashboard.php" class="' . ($current_page == 'student_dashboard.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/course.svg" alt="Courses" class="nav-icon">
                    <span class="nav-text">Courses</span>
                  </a>';
                    } elseif ($user_type == 'faculty') {
                        echo '<a href="faculty/faculty_dashboard.php" class="' . ($current_page == 'faculty_dashboard.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/course.svg" alt="Courses Handled" class="nav-icon">
                    <span class="nav-text">Courses</span>
                  </a>';
                    } elseif ($user_type == 'program_chair') {
                        echo '<a href="program_chair/program_chair_dashboard.php" class="' . ($current_page == 'program_chair_dashboard.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/department.svg" alt="Department Info" class="nav-icon">
                    <span class="nav-text">Department</span>
                  </a>';
                    }

                    // Highlight "Profile" link
                    echo '<a href="userprofile.php" class="' . ($current_page == 'userprofile.php' ? 'active' : '') . '">
                <img src="../frontend/assets/icons/account.svg" alt="Profile" class="nav-icon">
                <span class="nav-text">Profile</span>
              </a>';

                    // Highlight "Evaluate" link
                    if ($user_type == 'students') {
                        echo '<a href="students/student_evaluation.php" class="' . ($current_page == 'student_evaluation.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/evaluation.svg" alt="Evaluate" class="nav-icon">
                    <span class="nav-text">Evaluate</span>
                  </a>';
                    } elseif ($user_type == 'faculty') {
                        echo '<a href="faculty/faculty_evaluation.php" class="' . ($current_page == 'faculty_evaluation.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/evaluation.svg" alt="Evaluate" class="nav-icon">
                    <span class="nav-text">Evaluate</span>
                  </a>';
                    } elseif ($user_type == 'program_chair') {
                        echo '<a href="program_chair/program_chair_evaluation.php" class="' . ($current_page == 'program_chair_evaluation.php' ? 'active' : '') . '">
                    <img src="../frontend/assets/icons/evaluation.svg" alt="Evaluate" class="nav-icon">
                    <span class="nav-text">Evaluate</span>
                  </a>';
                    }

                    // Logout link
                    echo '<a href="../logout.php" onclick="return confirm(\'Are you sure you want to logout?\')" class="nav-link">
                <img src="../frontend/assets/icons/logout.svg" alt="Logout" class="nav-icon">
                <span class="nav-text">Logout</span>
              </a>';
                    ?>
                    <span class="active-indicator"></span>
                </div>
            </nav>

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



            </div>
            <div class="card" style="text-align:left; margin">
                <!-- Display user role -->
                <?php
                if ($user_type == 'students') {
                    echo "<p><strong>Role: </strong>Student</p>";
                } elseif ($user_type == 'faculty') {
                    echo "<p><strong>Role: </strong>Faculty</p>";
                } elseif ($user_type == 'program_chair') {
                    echo "<p><strong>Role: </strong>Program Chair</p>";
                }
                ?>
                <!-- Display Email as "Username" -->
                <p><strong>Email (Username):</strong> <?php echo htmlspecialchars($email); ?></p>

                <!-- Display department only for faculty and program chairs -->
                <?php if ($user_type == 'faculty' || $user_type == 'program_chair'): ?>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
                <?php endif; ?>
                <?php if ($user_type == 'students'): ?>
                    <p><strong>Enrolled Courses: </strong> <?php echo $num_courses; ?></p>
                <?php endif; ?>
            </div>

            <div class="card" style="text-align:left; margin">
                <h3>School Information</h3>
                <hr>
                <p>
                    <span style="font-weight:bold;">School Name: </span>
                    <span>(LPU-C) Lyceum of the Philippines University Cavite.</span>
                </p>
                <p>
                    <span style="font-weight:bold;">Time Zone</span>
                    <span>Asia/Honkong</span>
                </p>
                <p>
                    <span style="font-weight:bold;">Country:</span>
                    <span>Philippines</span>
                </p>
                <p>
                    <span style="font-weight:bold;">City/Town:</span>
                    <span>General Trias Cavite</span>
                </p>

                <div class="profile-actions">
                    <button onclick="openModal()">Change Profile Picture</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
                <div class="modal-header">
                    <h3>Change Profile Picture</h3>
                </div>
                <div class="modal-body">
                    <p>Do you want to change your profile image?</p>
                    <form action="userprofile.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                        <button type="submit" name="upload">Change Image</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()">Cancel</button>
                </div>
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