<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

if (isset($_GET['evaluation_period'])) {
    $period = $_GET['evaluation_period']; // Use the period from URL if set
} else {
    $period = $_SESSION['period_id'];
}


$selected_period_query = "SELECT semester, academic_year FROM evaluation_periods WHERE period_id = $period";
$selected_period_result = mysqli_query($con, $selected_period_query);

// Check if the selected_period_query was successful and if data was returned
if ($selected_period_result && mysqli_num_rows($selected_period_result) > 0) {
    // Fetch the data from the selected_period_result
    $row = mysqli_fetch_assoc($selected_period_result);
    $selected_semester = $row['semester'];
    $selected_academic_year = $row['academic_year'];
}

include ROOT_PATH . '/modules/generate_faculty_list/faculty_list_data_fetch.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Faculty List</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <?php include ROOT_PATH . '/frontend/layout/navbar.php'; ?>
    <style>
        .department-table {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <div>
                <h1>Faculty Ranking</h1>
            </div>
        </div>
        <div class="content">
            <div class="upperContent">
                <div>
                    <p>Showing faculty ranking for the <?= $selected_semester ?> Semester of Academic Year
                        <?= $selected_academic_year ?>.
                    </p>
                    <div class="select-container">
                        <div class="select-wrapper">
                            <form method="GET" action="">
                                <select id="evaluation_period" name="evaluation_period" class="custom-select"
                                    onchange="this.form.submit()">
                                    <option value="" disabled <?php echo empty($period) ? 'selected' : ''; ?>>Select
                                        Evaluation
                                        Period</option>
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
                <form action="generate_ranking.php" method="post">
                    <input type="hidden" value="<?= $selected_academic_year ?>" name="academic_year">
                    <input type="hidden" value="<?= $selected_semester ?>" name="semester">
                    <input type="hidden" value="<?= $period ?>" name="period_id">
                    <button type="submit" class="add-btn"><img
                            src="../../../frontend/assets/icons/pdf.svg">&nbsp;Generate
                        PDF&nbsp;
                    </button>
                </form>
            </div>
            <div class="departments-wrapper">
            <?php
                // Initialize ranking HTML string
                $ranking = '';

                // Loop through each department
                foreach ($faculty_list_by_department as $department_code => $faculty_list):
                    // Sort the faculty list by score in descending order
                    usort($faculty_list, function($a, $b) {
                        return $b['score'] <=> $a['score']; // Compare scores in descending order
                    });

                    $ranking .= '<div class="department-box">';
                    $ranking .= '<h3>' . htmlspecialchars($department_code) . ' Department</h3>';
                    $ranking .= '<div class="table">';
                    $ranking .= '<table>';
                    $ranking .= '<thead><tr>
                                    <th class="metadata" width="20px">Rank</th>
                                    <th class="metadata" width="20px">P.P</th>
                                    <th class="metadata">Faculty Name</th>
                                    <th class="metadata">Faculty id</th>
                                    <th class="metadata" width="140px"># of Courses</th>
                                    <th class="metadata">AVG</th>
                                    <th class="metadata">Score</th>
                                </thead>';
                    $ranking .= '<tbody>';

                    // Check if faculty list is not empty
                    if (!empty($faculty_list)) {

                        // Initialize rank counter
                        $rank = 1;
                        foreach ($faculty_list as $faculty) {
                            $ranking .= '<tr>';

                            // Check for top 3 ranks and add icon
                            if ($rank == 1) {
                                $ranking .= '<td><img src="../../../frontend/assets/icons/gold.svg"></td>';
                                $rank++;
                            } elseif ($rank == 2) {
                                $ranking .= '<td><img src="../../../frontend/assets/icons/silver.svg"></td>';
                                $rank++; // Silver medal for rank 2
                            } elseif ($rank == 3) {
                                $ranking .= '<td><img src="../../../frontend/assets/icons/bronze.svg"></td>';
                                $rank++; // Bronze medal for rank 3
                            } else {
                                $ranking .= '<td>' . $rank++ . '</td>'; // No icon for others
                            }

                            // Faculty profile and details
                            $ranking .= '<td><img class="profile-icon" src="../../../' . htmlspecialchars($faculty['profile_image']) . '" alt="Profile Image"></td>';
                            $ranking .= '<td><a href="../results/faculty_summary.php?facultyId=' . htmlspecialchars($faculty['faculty_id']) . '">' . htmlspecialchars($faculty['faculty_name']) . '</a></td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['faculty_id']) . '</td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['total_courses']) . '</td>';
                            $ranking .= '<td><b>' . htmlspecialchars($faculty['AVG']) . '</b></td>';
                            $ranking .= '<td><b>' . htmlspecialchars($faculty['score']) . '</b></td>';
                            $ranking .= '</tr>';
                        }
                    } else {
                        $ranking .= '<tr><td colspan="4" class="text-center">No faculty found for this department.</td></tr>';
                    }

                    $ranking .= '</tbody>';
                    $ranking .= '</table>';
                    $ranking .= '</div>';
                    $ranking .= '</div>';
                endforeach;

                // The $ranking variable now contains the entire HTML structure for the faculty list tables
                ?>


                <!-- You can print $ranking here for visual rendering in the browser -->
                <?php echo $ranking; ?>

            </div>
        </div>
        </div>
    </main>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>