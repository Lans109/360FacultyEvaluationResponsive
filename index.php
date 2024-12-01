<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Faculty Evaluation</title>
    <link rel="stylesheet" href="/360FacultyEvaluationSystem/End-Users/Styles/index-style.css">
</head>

<body>
    <nav class="topnav">
        <!-- Add navigation links here if needed -->
    </nav>

    <div class="container">
        <div class="wrapper">
            <div class="login-box">
                <div class="logo">
                    <img src="/360FacultyEvaluationSystem/End-Users/LPU-LOGO.png" alt="LPU Logo">
                </div>

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

                    switch ($role) {
                        case 'student':
                            header("Location: /360FacultyEvaluationSystem/End-Users/login-pages/students_login.php");
                            exit();
                        case 'faculty':
                            header("Location: /360FacultyEvaluationSystem/End-Users/login-pages/faculty_login.php");
                            exit();
                        case 'program_chair':
                            header("Location: /360FacultyEvaluationSystem/End-Users/login-pages/program_chair_login.php");
                            exit();
                        default:
                            echo "<p class='error-message'>Invalid role selected!</p>";
                            break;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>