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
                        <img src="../../../frontend/assets/dashboard.jpg">
                        <h3>Dashboard</h3>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dataDropdown"
                        onclick="toggleDropdown('dataCollapse')">
                        <img src="../../../frontend/assets/data.png" width="21px">
                        <h3>Data Management ▼</h3>
                    </a>
                    <div class="dropdown-content" id="dataCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/courses/courses.php">
                                    <img src="../../../frontend/assets/courses.jpg">
                                    <h3>Courses</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/programs/programs.php">
                                    <img src="../../../frontend/assets/programs.jpg">
                                    <h3>Programs</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/sections/sections.php">
                                    <img src="../../../frontend/assets/sections.jpg">
                                    <h3>Sections</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/students/students.php">
                                    <img src="../../../frontend/assets/students.jpg">
                                    <h3>Students</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/departments/departments.php">
                                    <img src="../../../frontend/assets/departments.jpg">
                                    <h3>Departments</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/faculty/faculty.php">
                                    <img src="../../../frontend/assets/faculty.jpg">
                                    <h3>Faculty</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/accounts/accounts.php">
                                    <img src="../../../frontend/assets/accounts.jpg">
                                    <h3>Accounts</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/survey/survey.php">
                                    <img src="../../../frontend/assets/survey.png" width="21px">
                                    <h3>Survey</h3>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../../backend/data_handling/evaluation/evaluation.php">
                        <img src="../../../frontend/assets/evaluations.jpg">
                        <h3>Evaluations</h3>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="viewResultsDropdown"
                        onclick="toggleDropdown('viewResultsCollapse')">
                        <img src="../../../frontend/assets/reports.jpg" width="21px">
                        <h3>Reports ▼</h3>
                    </a>
                    <div class="dropdown-content" id="viewResultsCollapse">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/results/results.php">
                                    <img src="../../../frontend/assets/reports.jpg">
                                    <h3>View Results</h3>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../../backend/data_handling/ranking/ranking.php">
                                    <img src="../../../frontend/assets/ranking.png" width="21px">
                                    <h3>Top Faculty</h3>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="../../../frontend/assets/signout.jpg">
                        <h3>Sign Out</h3>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <script>
        function toggleDropdown(dropdownId) {
            var dropdownContent = document.getElementById(dropdownId);
            dropdownContent.classList.toggle("show");
        }

        // Close all dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function (dropdown) {
                if (!e.target.closest('.dropdown')) {
                    dropdown.classList.remove("show");
                }
            }); 
        });
    </script>

</body>

</html>