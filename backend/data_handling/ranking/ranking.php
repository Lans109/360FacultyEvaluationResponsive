<?php
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';
include ROOT_PATH . '/modules/generate_faculty_list/faculty_list_data_fetch.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Faculty List</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>

    <?php include ROOT_PATH . '/frontend/layout/navbar.php'; ?>
    <style>
        .department-table {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Top Faculty</h1>
        </div>
        <div class="content">
            <div class="container mt-4">
                <?php
                // Initialize ranking HTML string
                $ranking = '';

                // Loop through each department
                foreach ($faculty_list_by_department as $department_code => $faculty_list):
                    $ranking .= '<h3 class="mt-5">' . htmlspecialchars($department_code) . ' Department</h3>';
                    $ranking .= '<div class="table">';
                    $ranking .= '<table>';
                    $ranking .= '<thead><tr>
                                    <th class="metadata">Rank</th>
                                    <th class="metadata">Faculty Name</th>
                                    <th class="metadata">Average Rating</th>
                                    <th class="metadata">No. of Courses</th></tr></thead>';
                    $ranking .= '<tbody>';

                    // Check if faculty list is not empty
                    if (!empty($faculty_list)) {
                        // Initialize rank counter
                        $rank = 1;
                        foreach ($faculty_list as $faculty) {
                            $ranking .= '<tr>';
                            $ranking .= '<td>' . $rank++ . '</td>'; // Rank number
                            $ranking .= '<td>' . htmlspecialchars($faculty['faculty_name']) . '</td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['AVG']) . '</td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['total_courses']) . '</td>';
                            $ranking .= '</tr>';
                        }
                    } else {
                        $ranking .= '<tr><td colspan="4" class="text-center">No faculty found for this department.</td></tr>';
                    }

                    $ranking .= '</tbody>';
                    $ranking .= '</table>';
                    $ranking .= '</div>';
                endforeach;

                // The $ranking variable now contains the entire HTML structure for the faculty list tables
                ?>

                <!-- You can print $ranking here for visual rendering in the browser -->
                <?php echo $ranking; ?>
                
                </div>
                <form action="generate_ranking.php" method="post">
                    <input type="hidden" value='<?php echo $ranking; ?>' name="ranking" id="ranking">
                    <button type="submit" class="enroll-btn">Generate PDF</button>
                </form>

            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>