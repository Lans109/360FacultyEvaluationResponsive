<?php

// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Fetch current evaluation status for the current date
$current_date = date('Y-m-d'); // Current date in YYYY-MM-DD format

// Query to fetch the active evaluation for today
$current_evaluation_query = "
    SELECT academic_year, semester, status, start_date, end_date 
    FROM evaluation_periods 
    WHERE status = 'active' 
    AND start_date <= '$current_date' 
    AND end_date >= '$current_date' 
    LIMIT 1"; 

$current_evaluation_result = mysqli_query($con, $current_evaluation_query);

// Check if any active evaluation exists for the current date
if ($current_evaluation_result && mysqli_num_rows($current_evaluation_result) > 0) {
    $current_evaluation_data = mysqli_fetch_assoc($current_evaluation_result);
} else {
    $current_evaluation_data = null;

    // If no active evaluation, fetch the closest upcoming evaluation
    $upcoming_evaluation_query = "
        SELECT academic_year, semester, start_date, end_date
        FROM evaluation_periods 
        WHERE start_date > '$current_date' 
        ORDER BY start_date ASC 
        LIMIT 1"; // Fetch the nearest future evaluation

    $upcoming_evaluation_result = mysqli_query($con, $upcoming_evaluation_query);

    if ($upcoming_evaluation_result && mysqli_num_rows($upcoming_evaluation_result) > 0) {
        $upcoming_evaluation_data = mysqli_fetch_assoc($upcoming_evaluation_result);
    } else {
        $upcoming_evaluation_data = null; // No upcoming evaluation
    }
}

// Query to fetch the ID of the active evaluation period
$period_query = "
    SELECT academic_year, semester, status, period_id, start_date, end_date
    FROM evaluation_periods 
    WHERE 
    start_date <= '$current_date' AND end_date >= '$current_date' 
    LIMIT 1"; // Fetch the active evaluation for today

$period_result = mysqli_query($con, $period_query);

// Check if any evaluation exists for the current date
if ($period_result && mysqli_num_rows($period_result) > 0) {
    $period_data = mysqli_fetch_assoc($period_result);

    // Store the period ID in the session
    $_SESSION['period_id'] = $period_data['period_id'];
} else {
    // No active evaluation period, clear session or set default value
    $_SESSION['period_id'] = null; // Or use a fallback like 0 if needed
}


// Fetch totals
$total_programs_query = "SELECT COUNT(*) AS total_programs FROM programs"; // Query for total programs
$total_courses_query = "SELECT COUNT(*) AS total_courses FROM courses";
$total_sections_query = "SELECT COUNT(*) AS total_sections FROM course_sections";
$total_students_query = "SELECT COUNT(*) AS total_students FROM students";
$total_faculty_query = "SELECT COUNT(*) AS total_faculty FROM faculty";

$total_programs_result = mysqli_query($con, $total_programs_query);
$total_courses_result = mysqli_query($con, $total_courses_query);
$total_sections_result = mysqli_query($con, $total_sections_query);
$total_students_result = mysqli_query($con, $total_students_query);
$total_faculty_result = mysqli_query($con, $total_faculty_query);

$total_programs = mysqli_fetch_assoc($total_programs_result)['total_programs'];
$total_courses = mysqli_fetch_assoc($total_courses_result)['total_courses'];
$total_sections = mysqli_fetch_assoc($total_sections_result)['total_sections'];
$total_students = mysqli_fetch_assoc($total_students_result)['total_students'];
$total_faculty = mysqli_fetch_assoc($total_faculty_result)['total_faculty'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>Dashboard</title>
    <?php include '../../../frontend/layout/navbar.php'; ?>

</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <div>
                <h1>Dashboard</h1>
            </div>
        </div>
        <div class="content">
            <h2>Evaluation Status</h2>
            <div class="banner">
                <?php if ($current_evaluation_data): ?>
                    <h3 class="card-title">Active Evaluation</h3>
                    <div class="evaluation-status-details">
                        <div>
                            <p class="card-text">Academic: <?php echo $current_evaluation_data['academic_year']; ?></p>
                            <p class="card-text">Semester: <?php echo $current_evaluation_data['semester']; ?></p>
                        </div>
                        <div>
                            <p class="card-text">Start Date: <?php echo $current_evaluation_data['start_date']; ?></p>
                            <p class="card-text">End Date: <?php echo $current_evaluation_data['end_date']; ?></p>
                        </div>
                    </div>
                <?php elseif ($upcoming_evaluation_data): ?>
                    <h3 class="card-title">Upcoming Evaluation</h3>
                    <div class="evaluation-status-details">
                        <div>   
                            <p class="card-text">Academic Year: <?php echo $upcoming_evaluation_data['academic_year']; ?></p>
                            <p class="card-text">Semester: <?php echo $upcoming_evaluation_data['semester']; ?></p>
                        </div>
                        <div>
                            <p class="card-text">Start Date: <?php echo $upcoming_evaluation_data['start_date']; ?></p>
                            <p class="card-text">End Date: <?php echo $upcoming_evaluation_data['end_date']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-header">No Active or Upcoming Evaluation</div>
                <?php endif; ?>
            </div>
            <div class="dashboard-content">
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-info">
                            <h3><?php echo $total_programs; ?></h3>
                            <p> Total Programs</p>
                        </div>
                        <div class="card-icon">
                            <img src="../../../frontend/assets/icons/program.svg">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-info">
                            <h3><?php echo $total_courses; ?></h3>
                            <p> Total Course</p>
                        </div>
                        <div class="card-icon">
                            <img src="../../../frontend/assets/icons/course.svg">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-info">
                            <h3><?php echo $total_students; ?></h3>
                            <p> Total Students</p>
                        </div>
                        <div class="card-icon">
                            <img src="../../../frontend/assets/icons/student.svg">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-info">
                            <h3><?php echo $total_sections; ?></h3>
                            <p> Total Sections</p>
                        </div>
                        <div class="card-icon">
                            <img src="../../../frontend/assets/icons/section.svg">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-info">
                            <h3><?php echo $total_faculty; ?></h3>
                            <p> Total Faculty</p>
                        </div>
                        <div class="card-icon">
                            <img src="../../../frontend/assets/icons/faculty.svg">
                        </div>
                    </div>


                </div>
                <div class="charts">
                    <?php include 'monitor.php'; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- sidebar opener -->
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>