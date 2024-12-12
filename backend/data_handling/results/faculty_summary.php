<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Selected Faculty
$facultyId = isset($_GET['facultyId']) ? $_GET['facultyId'] : 0;

// Check if 'evaluation_period' is passed in the URL
if (isset($_GET['evaluation_period'])) {
    $period = $_GET['evaluation_period']; // Use the period from URL if set
} else {
    $period = $_SESSION['period_id'];
}

// Query to fetch the semester and academic year for the selected period
$query = "SELECT semester, academic_year FROM evaluation_periods WHERE period_id = $period";
$result = mysqli_query($con, $query);

// Check if the query was successful and if data was returned
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the data from the result
    $row = mysqli_fetch_assoc($result);
    $selected_semester = $row['semester'];
    $selected_academic_year = $row['academic_year'];
}

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
                 WHERE f.faculty_id = '" . (mysqli_real_escape_string($con, $facultyId)) . "'";

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
    <div id="loader" class="loader"></div>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <div>
                <h1>Results</h1>
            </div>
        </div>
        <div class="content">
            <div class="container mt-5">
                <div class="faculty-profile">
                    <div class="profile-info">
                        <img class="profile-image" src="../../../<?= $facultyDetails['profile_image'] ?>">
                        <div class="faculty-info">
                            <h3><?php echo $facultyDetails['first_name'] . " " . $facultyDetails['last_name']; ?></h3>
                            <div><img class="icon" src="../../../frontend/assets/icons/message.svg">
                                <p><?php echo $facultyDetails['email']; ?></p>
                            </div>
                            <div><img class="icon" src="../../../frontend/assets/icons/call.svg">
                                <p><?php echo $facultyDetails['phone_number']; ?></p>
                            </div>
                            <div><img class="icon" src="../../../frontend/assets/icons/department.svg">
                                <p><?php echo $facultyDetails['department_name']; ?> -
                                    <?php echo $facultyDetails['department_code']; ?>
                                </p>
                            </div>

                            <div class="select-container">
                                <div class="select-wrapper">
                                    <form method="GET" action="">
                                        <select id="evaluation_period" name="evaluation_period" class="custom-select" onchange="this.form.submit()">
                                            <option value="" disabled <?php echo empty($period) ? 'selected' : ''; ?>>Select Evaluation Period</option>
                                            <?php
                                            // Fetch evaluation periods
                                            $query = "SELECT period_id, semester, academic_year FROM evaluation_periods ORDER BY academic_year DESC, semester ASC";
                                            $result = mysqli_query($con, $query);

                                            // Check and populate options
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $period_id = htmlspecialchars($row['period_id']);
                                                    $semester = htmlspecialchars($row['semester']);
                                                    $academic_year = htmlspecialchars($row['academic_year']);
                                                    // Check if the option should be selected
                                                    $selected = (isset($_GET['evaluation_period']) && $_GET['evaluation_period'] == $period_id) ? 'selected' : '';
                                                    echo "<option value='$period_id' $selected>$semester Sem, A.Y. $academic_year</option>";
                                                }
                                            } else {
                                                echo '<option value="" disabled>No evaluation periods available</option>';
                                            }
                                            ?>
                                        </select>
                                        <i class="fa fa-chevron-down select-icon"></i> <!-- Icon for dropdown -->
                                    </form>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <div class="upperContent">
                    <p>Showing summary for the <?= $selected_semester ?> Semester of Academic Year
                        <?= $selected_academic_year ?>.
                    </p>
                </div>
            </div>
            <!-- Display charts -->
            <div id="chart_div_student" style="width: 600px; height: 400px; visibility: hidden; position: absolute;">
            </div>
            <div id="chart_div_faculty" style="width: 600px; height: 400px; visibility: hidden; position: absolute;">
            </div>
            <div id="chart_div_chair" style="width: 600px; height: 400px; visibility: hidden; position: absolute;">
            </div>
            <div id="chart_div_self" style="width: 600px; height: 400px; visibility: hidden; position: absolute;"></div>
            <div class="charts">
                <div id="rating">
                    <form action="generate_summary.php" method="post">
                        <input type="hidden" id="period_id" name="period_id" value="<?php echo $period; ?>">
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
                                <img src="../../../frontend/assets/icons/pdf.svg">&nbsp;Generate PDF&nbsp;
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
                <div id="chart_div_overall"></div>
                <div id="combined_div_overall"></div>
            </div>

            <!-- Form for PDF generation -->

        </div>
        </div>
    </main>
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
</body>