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
        exit();
    }

    // File size validation (max size: 2MB)
    if ($file_size > 2097152) { // 2MB in bytes
        $_SESSION['profile_update_error'] = "File is too large. Maximum size is 2MB.";
        header("Location: userprofile.php");  // Redirect to avoid form resubmission
        exit();
    }

    // Generate a new unique file name
    $new_file_name = uniqid('', true) . '.' . $file_ext;

    // Upload directory
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the uploads folder if it doesn't exist
    }

    // Move the uploaded file to the uploads folder
    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
        // Now save the full path 'uploads/new_file_name' to the database
        $full_file_path = $upload_dir . $new_file_name;

        // Update the database with the new profile image path
        if ($user_type == 'students') {
            $update_sql = "UPDATE students SET profile_image = ? WHERE email = ?";
        } elseif ($user_type == 'faculty') {
            $update_sql = "UPDATE faculty SET profile_image = ? WHERE email = ?";
        } else {
            $update_sql = "UPDATE program_chairs SET profile_image = ? WHERE email = ?";
        }

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $full_file_path, $email);
        if ($stmt->execute()) {
            $_SESSION['profile_update_success'] = "Profile picture updated successfully.";
            $profile_image = $full_file_path; // Update the image displayed on the page with the full path
        } else {
            $_SESSION['profile_update_error'] = "Error updating profile picture.";
        }
        $stmt->close();

        // Redirect to prevent resubmission of the form
        header("Location: userprofile.php");
        exit();
    } else {
        $_SESSION['profile_update_error'] = "There was an error uploading the file.";
        header("Location: userprofile.php");
        exit();
    }
}

// Close the connection
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
        <div class="nav-title">
            <h1>
                Profile Information
            </h1>
        </div>
        <nav>
            <nav>
                <div class="nav-items">
                    <?php
                    // Get the current script name
                    $current_page = basename($_SERVER['PHP_SELF']);

                    // Dynamically generate navigation links based on user type
                    if ($user_type == 'students') {
                        echo '<a href="students/student_dashboard.php" class="' . ($current_page == 'student_dashboard.php' ? 'active' : '') . '">Courses</a>';
                    } elseif ($user_type == 'faculty') {
                        echo '<a href="faculty/faculty_dashboard.php" class="' . ($current_page == 'faculty_dashboard.php' ? 'active' : '') . '">Courses</a>';
                    } elseif ($user_type == 'program_chair') {
                        echo '<a href="program_chair/program_chair_dashboard.php" class="' . ($current_page == 'program_chair_dashboard.php' ? 'active' : '') . '">Department</a>';
                    }

                    // Highlight "Profile" link
                    echo '<a href="userprofile.php" class="' . ($current_page == 'userprofile.php' ? 'active' : '') . '">Profile</a>';

                    // Highlight "Evaluate" link
                    if ($user_type == 'students') {
                        echo '<a href="students/student_evaluation.php" class="' . ($current_page == 'student_evaluation.php' ? 'active' : '') . '">Evaluate</a>';
                    } elseif ($user_type == 'faculty') {
                        echo '<a href="faculty/faculty_evaluation.php" class="' . ($current_page == 'faculty_evaluation.php' ? 'active' : '') . '">Evaluate</a>';
                    } elseif ($user_type == 'program_chair') {
                        echo '<a href="program_chair/program_chair_evaluation.php" class="' . ($current_page == 'program_chair_evaluation.php' ? 'active' : '') . '">Evaluate</a>';
                    }

                    // Logout link
                    echo '<a href="../logout.php" onclick="return confirm(\'Are you sure you want to logout?\')">Logout</a>';
                    ?>
                    <span class="active-indicator"></span>
                </div>
            </nav>

        </nav>
    </div>


    <div class="container">
        <div class="card">
            <h1>About Me</h1>
            <div class="profile">
                <!-- Display profile image and make it clickable -->
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-pic"
                    onclick="openModal()">

                <!-- Display Full Name -->
                <h2><?php echo htmlspecialchars($name); ?></h2>


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
                    <span>Cavite City</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Modal for Profile Image Change -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Profile Picture</h3>
            </div>
            <div class="modal-body">
                <p>Do you want to change your profile image?</p>
                <form action="userprofile.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                    <button class="btn-change" type="submit" name="upload">Change Image</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" type="button" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>
</body>

</html>