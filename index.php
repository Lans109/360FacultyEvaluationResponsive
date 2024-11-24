<?php
// login.php
?>
<!DOCTYPE html>
<html>

<head>

    <title>Login</title>
    <nav class="topnav">
    </nav>
    <link rel='stylesheet' href="../End-Users/index-style.css">
    <title>Login - Faculty Evaluation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .btn {
        width: 100%;
        padding: 12px;
        background: #800000;
        color: white;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 10px;
        }

        .btn:hover {
            background: #600000;
        }
        </style>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="login-box">
                <div class="logo">
                    <img src="LPU-LOGO.png" alt="LPU Logo">
                </div>

                <!-- Buttons for Role Selection -->
                <!-- Buttons for Role Selection -->
                <form method="get" action="">
                    <button type="submit" name="role" value="student" class="btn">Login as Student</button>
                    <button type="submit" name="role" value="faculty" class="btn">Login as Faculty</button>
                    <button type="submit" name="role" value="program_chair" class="btn">Login as Program Chair</button>
                </form>

                <?php
                // Handle role-based redirection
                if (isset($_GET['role'])) {
                    $role = $_GET['role'];

                    // Check if the 'role' is set and redirect based on it
                    if ($role == 'student') {
                        header("Location: ../End-Users/students_login.php");
                        exit();
                    } elseif ($role == 'faculty') {
                        header("Location: ../End-Users/faculty_login.php");
                        exit();
                    } elseif ($role == 'program_chair') {
                        header("Location: ../End-Users/program_chair_login.php");
                        exit();
                    } else {
                        echo "<p class='error-message show'>Invalid role selected!</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>



</body>
</html>