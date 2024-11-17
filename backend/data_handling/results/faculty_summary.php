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
                 WHERE f.faculty_id = '" . (mysqli_real_escape_string($con, $facultyId)+1) . "'";

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
                <h2> Faculty Profile</h2>
                <div class="faculty-profile">

                    <div class="banner-profile">
                        <div class="profile">
                            <div class="profile-image">
                                <!-- Faculty Profile Section -->
                                <img class="profile-img" src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250" alt="Faculty Profile Image">
                            </div>
                            <?php if ($facultyDetails): ?>
                                <div class="faculty-details">
                                    <h2><strong><?php echo htmlspecialchars($facultyDetails['faculty_name']); ?></strong></h2>
                                    <p>ID: <?php echo htmlspecialchars($facultyDetails['faculty_id']); ?></p>
                                    <p>Email: <?php echo htmlspecialchars($facultyDetails['email']); ?></p>
                                    <p>Department: <?php echo htmlspecialchars($facultyDetails['department_name']); ?></p>
                                </div>
                            <?php else: ?>
                                <p>Faculty details not found.</p>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                        
                    </div>
                    <!-- Display charts -->
                    <div id="chart_div_student" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_faculty" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_chair" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_self" style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                    <div class="charts">
                        <div id="rating">
                        <form action="generate_summary.php" method="post">
                        <div class="rating"><?= round($overallTotal, 2) ?></div>
                            <div class="center">
                                <?php
                                // Check the range of the score and display the appropriate evaluation
                                if ($overallTotal >= 4.5) {
                                    echo '<h3>Excellent</h3><br><p>Performance consistently exceeds expectations, demonstrating a high level of effectiveness and quality.</p><br>';
                                } elseif ($overallTotal >= 3.5 && $overallTotal < 4.5) {
                                    echo '<h3>Good</h3><br><p>Performance consistently meets or occasionally exceeds expectations. Reliable and effective.</p><br>';
                                } elseif ($overallTotal >= 2.5 && $overallTotal < 3.5) {
                                    echo '<h5>Satisfactory</h5><br><p>Performance meets expectations. There is room for improvement but generally acceptable.</p><br>';
                                } elseif ($overallTotal >= 1.5 && $overallTotal < 2.5) {
                                    echo '<h5>Fair</h5><br><p>Performance occasionally meets expectations but lacks consistency. Some improvement is needed.</p><br>';
                                } else {
                                    echo '<h5>Poor</h5><br><p>Performance consistently does not meet expectations. Significant improvement is needed.</p><br>';
                                }
                                ?>
                                <button type="submit" class="generate-pdf-btn">Generate PDF</button>
                            </div>
                            
                            <input type="hidden" id="facultyId" name="facultyId" value="<?php echo $facultyId; ?>">
                            <input type="hidden" id="period" name="period" value="<?php echo $period; ?>">
                            <input type="hidden" id="studentImageData" name="studentImageData" value="">
                            <input type="hidden" id="facultyImageData" name="facultyImageData" value="">
                            <input type="hidden" id="chairImageData" name="chairImageData" value="">
                            <input type="hidden" id="selfImageData" name="selfImageData" value="">
                            <input type="hidden" id="overallImageData" name="overallImageData" value="">
                            <input type="hidden" id="combinedImageData" name="combinedImageData" value="">
                            
                        </form>
                        </div> 
                        <div id="chart_div_overall"></div>
                        <div id="combined_div_overall"></div>
                    </div>
                
                <!-- Form for PDF generation -->
                
            </div>
        </div>
    </main>
</body>
