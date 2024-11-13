<?php 
// student-dashboard.php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .nav-links li a:hover,
        .nav-links li a.active {
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
        /* Dashboard Specific */
        .welcome-section h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 24px;
            font-weight: normal;
        }
        .academic-year-box {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #6B0007;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .academic-year-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .evaluation-status {
            font-size: 14px;
            color: #666;
        }
        .reminder-box {
            background-color: #FFF9E5;
            padding: 16px;
            border-radius: 4px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-sidebar">
        <img src="LPU-LOGO.png" alt="LPU Logo">
    </div>
    <ul class="nav-links">
        <li><a href="#" class="active">
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
    <a href="user-profile.php" class="user-profile">
        <img src="pfp.jpg" alt="User">
        <span>User</span>
    </a>
</nav>
<div class="main-content">
    <div class="welcome-section">
        <h1>Welcome User!</h1>
    </div>
    <div class="academic-year-box">
        <div class="academic-year-title">Academic Year 2024-2025 1st Semester</div>
        <div class="evaluation-status">Evaluation Status: In Progress</div>
    </div>
    <div class="reminder-box">
        Reminder: It's time to complete your faculty evaluation! Please take a few moments to provide your feedback.
    </div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">11</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">4</div>
            <div class="stat-label">Total Courses</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">9</div>
            <div class="stat-label">Total Subjects</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">5</div>
            <div class="stat-label">Total Sections</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">116</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">11</div>
            <div class="stat-label">Total Faculty</div>
        </div>
    </div>
</div>
</body>
</html>