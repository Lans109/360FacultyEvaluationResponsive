<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin-style.css">
</head>

<body>
    <aside id="sidebar">
        <div class="top">
            <div class="logo">
                <img src="../../../frontend/assets/LPU Black.png" width="120" height="auto">
            </div>
            <span class="close-btn" onclick="toggleSidebar()">✖</span>
        </div>
        <div class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="../../../backend/data_handling/dashboard/dashboard.php">
                        <img src="../../../frontend/assets/icons/monitor.svg">
                        <h3>Dashboard</h3>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dataDropdown" onclick="toggleDropdown('dataCollapse', this)">
                        <img src="../../../frontend/assets/icons/data.svg">
                        <h3>Data Management</h3>
                        <span id="drop-down-arrow"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
                    </a>
                    <div class="dropdown-content" id="dataCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/faculty/faculty.php">
                                    <img src="../../../frontend/assets/icons/faculty.svg">
                                    <h3>Faculty</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/students/students.php">
                                    <img src="../../../frontend/assets/icons/student.svg">
                                    <h3>Students</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/courses/courses.php">
                                    <img src="../../../frontend/assets/icons/course.svg">
                                    <h3>Courses</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/programs/programs.php">
                                    <img src="../../../frontend/assets/icons/program.svg">
                                    <h3>Programs</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/departments/departments.php">
                                    <img src="../../../frontend/assets/icons/department.svg">
                                    <h3>Departments</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/survey/survey.php">
                                    <img src="../../../frontend/assets/icons/survey.svg">
                                    <h3>Survey</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/sections/sections.php">
                                    <img src="../../../frontend/assets/icons/section.svg">
                                    <h3>Sections</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/accounts/accounts.php">
                                    <img src="../../../frontend/assets/icons/account.svg">
                                    <h3>Accounts</h3>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../../backend/data_handling/evaluation/evaluation.php">
                        <img src="../../../frontend/assets/icons/evaluation.svg">
                        <h3>Evaluations</h3>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="viewResultsDropdown" onclick="toggleDropdown('viewResultsCollapse', this)">
                        <img src="../../../frontend/assets/icons/report.svg">
                        <h3>Reports</h3>
                        <span id="view-results-arrow"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
                    </a>
                    <div class="dropdown-content" id="viewResultsCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/results/results.php">
                                    <img src="../../../frontend/assets/icons/result.svg">
                                    <h3>View Results</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/ranking/ranking.php">
                                    <img src="../../../frontend/assets/icons/ranking.svg">
                                    <h3>Top Faculty</h3>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../../backend/data_handling/sign_out.php">
                        <img src="../../../frontend/assets/icons/logout.svg">
                        <h3>Sign Out</h3>
                    </a>
                </li>
            </ul>
            
            <footer>
                <h4>Orbits v1.0.0 - First Light</h4><br>
                <h4><i class="fa fa-copyright" aria-hidden="true"></i> 2024 Orbits. All rights reserved.</h4>
            </footer>
        </div>
    </aside>

    <script>
        function toggleDropdown(dropdownId, link) {
            var dropdownContent = document.getElementById(dropdownId);
            const arrow = link.querySelector("span"); // Get the span containing the arrow

            // Toggle the dropdown visibility
            dropdownContent.classList.toggle("show");

            // Get the current rotation value
            const currentRotation = arrow.style.transform || 'rotate(0deg)';
            const match = currentRotation.match(/rotate\((\d+)deg\)/);
            const newRotation = match ? parseInt(match[1]) + 90 : 0;

            // If the dropdown is visible, rotate it 45 degrees, else reset to 0 degrees
            if (dropdownContent.classList.contains("show")) {
                arrow.style.transform = `rotate(${newRotation}deg)`;
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Close all dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            var dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function (dropdown) {
                if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-toggle')) {
                    dropdown.classList.remove("show");
                    // Reset the rotation when closing dropdown
                    var arrow = dropdown.previousElementSibling.querySelector("span");
                    arrow.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>

</body>

</html>
