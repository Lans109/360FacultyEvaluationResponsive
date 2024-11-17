<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

$facultyId = isset($_GET['facultyId']) ? $_GET['facultyId'] : 0;
$period = isset($_GET['period']) ? $_GET['period'] : 1;

// Fetch faculty details
$facultyQuery = "SELECT f.faculty_id, CONCAT(f.first_name, ' ', f.last_name) AS faculty_name, f.email, d.department_name 
                 FROM faculty f 
                 LEFT JOIN departments d ON f.department_id = d.department_id 
                 WHERE f.faculty_id = '" . mysqli_real_escape_string($con, $facultyId) . "'";

$facultyResult = mysqli_query($con, $facultyQuery);
$facultyDetails = mysqli_fetch_assoc($facultyResult);

include ROOT_PATH . '/modules/generate_report/report_data_fetch.php';
include ROOT_PATH . '/modules/generate_report/report_data_results.php';
include ROOT_PATH . '/modules/generate_report/report_data_graph.php';
?>

<head>
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
</head>

<body>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <h1>Results</h1>
        </div>
        <div class="content">
            <div class="container mt-5">

                <!-- Faculty Profile Section -->
                <?php if ($facultyDetails): ?>
                    <div class="faculty-profile">
                        <h2>Faculty Profile</h2>
                        <p><strong>Faculty ID:</strong> <?php echo htmlspecialchars($facultyDetails['faculty_id']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($facultyDetails['faculty_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($facultyDetails['email']); ?></p>
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($facultyDetails['department_name']); ?></p>
                    </div>
                <?php else: ?>
                    <p>Faculty details not found.</p>
                <?php endif; ?>

                <!-- Display charts -->
                <div id="chart_div_student" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                <div id="chart_div_faculty" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                <div id="chart_div_chair" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                <div id="chart_div_self" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                
                <div id="chart_div_overall" style="width: 900px; height: 500px;"></div>
                <div id="combined_div_overall" style="width: 100%; height: 500px;"></div>
                

                <!-- Form for PDF generation -->
                <form action="generate_summary.php" method="post">
                    <input type="hidden" id="facultyId" name="facultyId" value="<?php echo $facultyId; ?>">
                    <input type="hidden" id="period" name="period" value="<?php echo $period; ?>">
                    <input type="hidden" id="studentImageData" name="studentImageData" value="">
                    <input type="hidden" id="facultyImageData" name="facultyImageData" value="">
                    <input type="hidden" id="chairImageData" name="chairImageData" value="">
                    <input type="hidden" id="selfImageData" name="selfImageData" value="">
                    <input type="hidden" id="overallImageData" name="overallImageData" value="">
                    <input type="hidden" id="combinedImageData" name="combinedImageData" value="">
                    <button type="submit" class="enroll-btn">Generate PDF</button>
                </form>
            </div>
        </div>
    </main>
</body>
