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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
            <div><h1>Top Faculty</h1></div>
        </div>
        <div class="content">
            <div class="departments-wrapper">
                <?php
                // Initialize ranking HTML string
                $ranking = '';

                // Loop through each department
                foreach ($faculty_list_by_department as $department_code => $faculty_list):
                    $ranking .= '<div class="department-box">';
                    $ranking .= '<h3>' . htmlspecialchars($department_code) . ' Department</h3>';
                    $ranking .= '<div class="table">';
                    $ranking .= '<table>';
                    $ranking .= '<thead><tr>
                                    <th class="metadata" width="20px">Rank</th>
                                    <th class="metadata" width="20px">P.P</th>
                                    <th class="metadata">Faculty Name</th>
                                    <th class="metadata">Faculty id</th>
                                    <th class="metadata" width="140px"># of Courses</th></th>
                                    <th class="metadata">AVG</th>
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
                            $ranking .= '<td><img class="profile-icon" src="../../../' . htmlspecialchars($faculty['profile_image']) . '" alt="Profile Image"></td>';
           
                            $ranking .= '<td><a href="../results/faculty_summary.php?facultyId=' . htmlspecialchars($faculty['faculty_id'])-1 . '&period=1">' . htmlspecialchars($faculty['faculty_name']) . '</a></td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['faculty_id']) . '</td>';
                            $ranking .= '<td>' . htmlspecialchars($faculty['total_courses']) . '</td>';
                            $ranking .= '<td><b>' . htmlspecialchars($faculty['AVG']) . '</b></td>';
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
                <form action="generate_ranking.php" method="post">  
                    <button type="submit" class="add-btn"><img src="../../../frontend/assets/icons/pdf.svg">&nbsp;Generate PDF&nbsp;</button>
                </form>
            </div>
        </div>
    </main>
    
    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>