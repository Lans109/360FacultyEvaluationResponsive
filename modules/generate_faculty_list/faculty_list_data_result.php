<?php
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';
include ROOT_PATH . '/modules/generate_faculty_list/faculty_list_data_fetch.php';

                $ranking = '';

                // Loop through each department
                foreach ($faculty_list_by_department as $department_code => $faculty_list):
                    $ranking .= '<div class="department-box">';
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
                    $ranking .= '</div>';
                endforeach;



