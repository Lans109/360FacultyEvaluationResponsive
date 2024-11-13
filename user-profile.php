<?php
// user-profile.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #fff;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 200px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .logo-sidebar {
            margin-bottom: 40px;
            text-align: center;
        }

        .logo-sidebar img {
            max-width: 100px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .nav-links li a:hover {
            background-color: #f0f0f0;
        }

        /* Top Navigation */
        .topnav {
            position: fixed;
            top: 0;
            left: 200px;
            right: 0;
            background-color: #6B0007;
            padding: 12px 24px;
            display: flex;
            justify-content: flex-end;
            z-index: 100;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
        }

        .user-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        /* Main Content */
        .main-content {
            margin-left: 200px;
            padding: 80px 24px 24px;
            position: relative;
            z-index: 2;
        }

        /* Profile Specific Styles */
        .page-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 24px;
        }

        .profile-header {
            position: relative;
            margin-bottom: 120px;
        }

        .profile-banner {
            height: 200px;
            background: linear-gradient(45deg, #ffe5e5, #fff0f3);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .profile-banner::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle, white 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.3;
        }

        .profile-picture {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid white;
            position: absolute;
            bottom: -70px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }

        .profile-role {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
        }

        .profile-enrollment {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
        }

        .courses-enrolled {
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .course-card {
            background: white;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .course-card.black { border-left: 4px solid #333; }
        .course-card.purple { border-left: 4px solid #6B46C1; }
        .course-card.green { border-left: 4px solid #2F855A; }

        .course-info {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .course-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 16px;
            color: inherit;
        }

        .instructor-name {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .instructor-role {
            font-size: 14px;
            color: #666;
        }

        .course-card.purple .course-title,
        .course-card.purple .instructor-name {
            color: #6B46C1;
        }

        .course-card.green .course-title,
        .course-card.green .instructor-name {
            color: #2F855A;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-sidebar">
            <img src="LPU-LOGO.png" alt="LPU Logo">
        </div>
        <ul class="nav-links">
            <li><a href="student-dashboard.php">
                <span>📊</span>
                Dashboard
            </a></li>
            <li><a href="faculty-evaluation-list.php">
                <span>👥</span>
                Faculty
            </a></li>
            <li><a href="index.php">
                <span>🚪</span>
                Sign out
            </a></li>
        </ul>
    </div>

    <nav class="topnav">
        <div class="user-profile">
            <img src="pfp.jpg" alt="User">
            <span>User</span>
        </div>
    </nav>

    <div class="main-content">
        <h1 class="page-title">User Profile</h1>

        <div class="profile-header">
            <div class="profile-banner"></div>
            <img src="pfp.jpg" alt="Profile Picture" class="profile-picture">
        </div>

        <div class="profile-info">
            <div class="profile-name">Prince Pipen</div>
            <div class="profile-role">Student</div>
            <div class="profile-enrollment">Enrolled: BSCS301 A.Y. 2024-2025</div>
            <div class="courses-enrolled">Courses Enrolled:</div>
        </div>

        <div class="courses-grid">
            <div class="course-card black">
                <div class="course-info">1st 2425 | CSCN10C</div>
                <div class="course-title">SOFTWARE ENGINEERING 1</div>
                <div class="instructor-name">Dr. Peren, Jerian</div>
                <div class="instructor-role">COECSA</div>
            </div>

            <div class="course-card purple">
                <div class="course-info">1st 2425 | CSCN10C</div>
                <div class="course-title">SOFTWARE ENGINEERING 1</div>
                <div class="instructor-name">Dr. Peren, Jerian</div>
                <div class="instructor-role">COECSA</div>
            </div>

            <div class="course-card green">
                <div class="course-info">1st 2425 | CSCN10C</div>
                <div class="course-title">SOFTWARE ENGINEERING 1</div>
                <div class="instructor-name">Dr. Peren, Jerian</div>
                <div class="instructor-role">COECSA</div>
            </div>
        </div>
    </div>
</body>
</html>