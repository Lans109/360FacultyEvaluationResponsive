<?php
// process_login.php
session_start();
include('databasecon.php'); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input data
    $email = trim($_POST['email']); // Email entered by the user
    $input_password = trim($_POST['password']); // Password entered by the user
    $user_type = $_POST['user_type']; // User type (students, faculty, program_chair)

    // Determine the table to query based on user type
    $tableName = '';
    switch ($user_type) {
        case 'students':
            $tableName = 'students';
            break;
        case 'faculty':
            $tableName = 'faculty';
            break;
        case 'program_chair':
            $tableName = 'program_chairs';
            break;
        default:
            echo "Invalid user type.";
            exit();
    }

    // Fetch user data (email, password, first_name, last_name) from the respective table based on the user type
    $stmt = $conn->prepare("SELECT email, password, first_name, last_name FROM $tableName WHERE LOWER(email) = LOWER(?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_email, $db_password, $db_first_name, $db_last_name); // Fetch first_name and last_name
        $stmt->fetch();

        // Compare passwords
        if ($input_password === $db_password) {
            // Password matched, set session variables
            $_SESSION['email'] = $db_email;
            $_SESSION['name'] = $db_first_name . ' ' . $db_last_name;  // Combine first and last name
            $_SESSION['user_type'] = $user_type; // Store user type in session
            $_SESSION['loggedin'] = true;

            // Redirect based on the user type
            if ($user_type == 'students') {
                header("Location: student_dashboard.php");
            } elseif ($user_type == 'faculty') {
                header("Location: faculty_dashboard.php");
            } elseif ($user_type == 'program_chair') {
                header("Location: program_chair_dashboard.php");
            }
            exit();
        } else {
            // Invalid password
            header("Location: " . $user_type . "_login.php?error=Invalid email or password.");
            exit();
        }
    } else {
        // User not found
        header("Location: " . $user_type . "_login.php?error=User not found.");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
