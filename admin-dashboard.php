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
    <?php include 'admin.sidebar.php'; ?>

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