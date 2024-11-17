<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

$facultyId = isset($_GET['facultyId']) ? $_GET['facultyId'] : 0;
$period = isset($_GET['period']) ? $_GET['period'] : 1;

include ROOT_PATH . '/modules/generate_report/data_fetch.php';
include ROOT_PATH . '/modules/generate_report/data_results.php';
include ROOT_PATH . '/modules/generate_report/data_graph.php';
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
                <!-- Faculty and Period selection form -->
                <form action="results.php" method="get">
                    <label for="facultyId">Select Faculty:</label>
                    <select name="facultyId" id="facultyId">
                        <?php foreach ($facultyList as $faculty): ?>
                            <option value="<?php echo ($faculty['faculty_id'] - 1); ?>" <?php echo (($faculty['faculty_id'] - 1) == $facultyId) ? 'selected' : ''; ?>>
                                <?php echo $faculty['faculty_name'] . ' (ID: ' . ($faculty['faculty_id'] - 1) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="period">Select Period:</label>
                    <input type="number" name="period" id="period" value="<?php echo $period; ?>" min="1">

                    <button type="submit" class="add-btn">Load Data</button>
                </form>

                <!-- Display charts -->
                <div id="chart_div_student"
                    style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                <div id="chart_div_faculty"
                    style="width: 900px; height: 500px; visibility: hidden; position: absolute;"></div>
                <div id="chart_div_chair" style="width: 900px; height: 500px; visibility: hidden; position: absolute;">
                </div>
                <div id="chart_div_self" style="width: 900px; height: 500px; visibility: hidden; position: absolute;">
                </div>
                <div id="chart_div_overall" style="width: 900px; height: 500px;"></div>
                <div id="combined_div_overall" style="width: 100%; height: 500px;"></div>

                <!-- Form for PDF generation -->
                <form action="generate_PDF.php" method="post">
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