<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

$facultyId = isset($_GET['facultyId']) ? $_GET['facultyId'] : 0;
$period = isset($_GET['period']) ? $_GET['period'] : 1;

// Fetch faculty details
$facultyQuery = "SELECT 
                    f.faculty_id, 
                    f.first_name, 
                    f.last_name, 
                    f.email, 
                    f.phone_number, 
                    d.department_code,
                    d.department_name,
                    d.department_id,
                    f.profile_image
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <div><h1>Results</h1></div>
        </div>
        <div class="content">
            <div class="container mt-5">
                <div class="faculty-profile">
                    <div class="profile-info">
                        <img class="profile-image" src="../../../<?= $facultyDetails['profile_image'] ?>">
                        <div class="faculty-info">
                            <h3><?php echo $facultyDetails['first_name'] . " " . $facultyDetails['last_name']; ?></h3>
                            <div><img class="icon" src="../../../frontend/assets/icons/message.svg"><p><?php echo $facultyDetails['email']; ?></p></div>
                            <div><img class="icon" src="../../../frontend/assets/icons/call.svg"><p><?php echo $facultyDetails['phone_number']; ?></p></div>
                            <div><img class="icon" src="../../../frontend/assets/icons/department.svg"><p><?php echo $facultyDetails['department_name']; ?> - <?php echo $facultyDetails['department_code']; ?></p></div>
                           
                        </div>       
                    </div>
                </div>
                        
                    </div>
                    <!-- Display charts -->
                    <div id="chart_div_student" style="width: 600px; height: 400px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_faculty" style="width: 600px; height: 400px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_chair" style="width: 600px; height: 400px; visibility: hidden; position: absolute;"></div>
                    <div id="chart_div_self" style="width: 600px; height: 400px; visibility: hidden; position: absolute;"></div>
                    <div class="charts">
                    <div id="chart_div_overall"></div>
                        <div id="rating">
                        <form action="generate_summary.php" method="post">
                        <div class="rating"><?= round($overallTotal, 2) ?></div>
                            <div class="rating-info">
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
                                <button type="submit" class="add-btn">
                                    <img src="../../../frontend/assets/icons/pdf.svg">&nbsp;Geberate PDF&nbsp;
                                </button>
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
                        
                        <div id="combined_div_overall"></div>
                    </div>
                
                <!-- Form for PDF generation -->
                
            </div>
        </div>
    </main>
</body>
