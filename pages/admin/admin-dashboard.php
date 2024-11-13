<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='admin-style.css'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script type="text/javascript" src="app.js" defer></script>
    <title>360 Faculty Evaluation System</title>

    <nav class="topnav">
        <span class="open-menu" onclick="toggleSidebar()">☰</span>
    </nav>
</head>

<body>
    <!-- side section -->
    <aside id="sidebar">
        <div class="top">
            <div class="logo">
                <img src="LPU-LOGO.png" width="120" height="auto">
                <!-- <h2>C<span class="danger">BABAR</span></h2> -->
            </div>
            <span class="close-btn" onclick="toggleSidebar()">✖</span>
        </div>
        <!-- end of top side -->

        <div class="sidebar">
            <a href="admin-dashboard.php">
                <img src="icons/dashboard.jpg">
                <h3>Dashboard</h3>
            </a>
            <a href="admin-courses.php">
                <img src="icons/course.jpg">
                <h3>Course</h3>
            </a>
            <a href="#">
                <img src="icons/program.jpg">
                <h3>Program</h3>
            </a>
            <a href="#">
                <img src="icons/section.jpg">
                <h3>Section</h3>
            </a>
            <a href="#">
                <img src="icons/students.jpg">
                <h3>Students</h3>
            </a>
            <a href="#">
                <img src="icons/departments.jpg">
                <h3>Departments</h3>
            </a>
            <a href="#">
                <img src="icons/faculty.jpg">
                <h3>Faculty</h3>
            </a>
            <a href="#">
                <img src="icons/accounts.jpg">
                <h3>Accounts</h3>
            </a>
            <a href="#">
                <img src="icons/evaluations.jpg">
                <h3>Evaluations</h3>
            </a>
            <a href="#">
                <img src="icons/reports.jpg">
                <h3>Reports</h3>
            </a>
            <a href="#">
                <img src="icons/signout.jpg">
                <h3>Sign Out</h3>
            </a>
        </div>
    </aside>

    <!-- side section ends -->

    <!-- main section start -->
    <main>

        <div class="upperMain">
            <h1>Dashboard</h1>
        </div>
        <div class="content">
            <h2> Welcome ! </h2>
            <!-- session name nalang dito -->
            <div class="banner">
                <!-- backend na this design ko nalang pag lalagyan -->
                <h3>Academic Year 2024-2025 1st Semester</h3><br>
                <p>Evaluation Status: In Progress</p>
            </div>
            <div class="reminder">
                <p>Reminder: It's time to complete your faculty evaluation! Please take a few moments to provide
                    your feedback.</p>
            </div>
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>11</h3>
                        <p> total users</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/accounts.jpg">
                    </div>
                </div>
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>4</h3>
                        <p> total course</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/students.jpg">
                    </div>
                </div>
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>9</h3>
                        <p> total subjects</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/evaluations.jpg">
                    </div>
                </div>
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>5</h3>
                        <p> total sections</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/section.jpg">
                    </div>
                </div>
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>5</h3>
                        <p> total students</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/section.jpg">
                    </div>
                </div>
                <div class="card">
                    <div class="card-info">
                        <!-- from database placeholder lang  -->
                        <h3>5</h3>
                        <p> total faculty</p>
                    </div>
                    <div class="card-icon">
                        <img src="icons/faculty.jpg">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- main section ends -->
</body>

</html>