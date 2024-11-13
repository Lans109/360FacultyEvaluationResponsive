<?php
// Mock database for demonstration (Dito lalagay nila CJ and Lingag yung Database)
session_start();

if (!isset($_SESSION['courses'])) {
    $_SESSION['courses'] = [
        ['code' => 'CSCN08C', 'name' => 'Information Assurance and Security', 'description' => 'Focuses on protecting data confidentiality, integrity, and availability through policies, technologies, and risk management strategies.', 'department' => 'COECSA', 'students' => 25],
        ['code' => 'CSCN07C', 'name' => 'Architecture and Organization', 'description' => 'This course focuses on the structure and behavior of the computer system and refers to the logical and abstract aspects of system implementation as seen by the programmer.', 'department' => 'COECSA', 'students' => 20],
        ['code' => 'DCSN06C', 'name' => 'Application Development and Emerging Technologies', 'description' => 'This course provides a foundational understanding of application development and the latest technologies shaping the industry.', 'department' => 'COECSA', 'students' => 25],
    ];
}

// Handle course addition and updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new course
            $newCourse = [
                'code' => $_POST['course_code'],
                'name' => $_POST['course_name'],
                'description' => $_POST['course_description'],
                'department' => $_POST['course_department'],
                'students' => $_POST['course_students']
            ];
            $_SESSION['courses'][] = $newCourse; // Add the new course to the session
        } elseif ($_POST['action'] === 'edit') {
            // Edit existing course
            $index = $_POST['index'];
            $_SESSION['courses'][$index] = [
                'code' => $_POST['course_code'],
                'name' => $_POST['course_name'],
                'description' => $_POST['course_description'],
                'department' => $_POST['course_department'],
                'students' => $_POST['course_students']
            ];
        } elseif ($_POST['action'] === 'delete') {
            // Delete course
            $index = $_POST['index'];
            unset($_SESSION['courses'][$index]);
            $_SESSION['courses'] = array_values($_SESSION['courses']); // Reindex array
        }
    }
}

// Pagination logic
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$totalCourses = count($_SESSION['courses']);
$totalPages = ceil($totalCourses / $perPage);
$offset = ($currentPage - 1) * $perPage;

// Slicing the array for pagination
$displayCourses = array_slice($_SESSION['courses'], $offset, $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360 Faculty Evaluation System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .edit-btn, .delete-btn {
            background-color: #90EE90;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-btn {
            background-color: #6B0007;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .delete-btn {
            display: none;
        }
    </style>
    <link rel='stylesheet' href='admin-style.css'>
    <script>
        function toggleDelete(index) {
            // Hide all delete buttons
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => btn.style.display = 'none');

            // Show the selected delete button
            const deleteButton = document.getElementById('delete-' + index);
            deleteButton.style.display = 'inline-block';
        }
    </script>
</head>
<body>
    <nav class="topnav">
        <div style="display: flex; justify-content: flex-end; padding-right: 20px; align-items: center; color: white;">
            Admin
        </div>
    </nav>

    <aside>
        <div class="sidepanel">
            <div class="top">
                <div class="logo">
                    <img src="LPU-LOGO.png" alt="LPU Logo" width="90" height="auto">
                </div>
            </div>
            <div class="sidebar">
                <a href="admin-dashboard.php"><h3>Dashboard</h3></a>
                <a href="admin-courses.php"><h3>Course</h3></a>
                <a href="#"><h3>Program</h3></a>
                <a href="#"><h3>Section</h3></a>
                <a href="#"><h3>Students</h3></a>
                <a href="#"><h3>Departments</h3></a>
                <a href="#"><h3>Faculty</h3></a>
                <a href="#"><h3>Accounts</h3></a>
                <a href="#"><h3>Evaluations</h3></a>
                <a href="#"><h3>Reports</h3></a>
                <a href="#"><h3>Sign Out</h3></a>
            </div>
        </div>
    </aside>

    <main>
        <div class="upperMain">
            <h1>Courses</h1>
        </div>
        <div class="content">
            <!-- Add Courses Form -->
            <form method="POST" style="margin-bottom: 20px;">
                <input type="text" name="course_code" placeholder="Course Code" required>
                <input type="text" name="course_name" placeholder="Course Name" required>
                <input type="text" name="course_description" placeholder="Course Description" required>
                <input type="text" name="course_department" placeholder="Course Department" required>
                <input type="number" name="course_students" placeholder="No. of Students" required>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="add-btn">Add Course</button>
            </form>

            <!-- Courses Table -->
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Description</th>
                        <th>Course Department</th>
                        <th>No. of Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($displayCourses as $index => $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['code']); ?></td>
                        <td><?php echo htmlspecialchars($course['name']); ?></td>
                        <td><?php echo htmlspecialchars($course['description']); ?></td>
                        <td><?php echo htmlspecialchars($course['department']); ?></td>
                        <td><?php echo htmlspecialchars($course['students']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($course['code']); ?>">
                                <input type="hidden" name="course_name" value="<?php echo htmlspecialchars($course['name']); ?>">
                                <input type="hidden" name="course_description" value="<?php echo htmlspecialchars($course['description']); ?>">
                                <input type="hidden" name="course_department" value="<?php echo htmlspecialchars($course['department']); ?>">
                                <input type="hidden" name="course_students" value="<?php echo htmlspecialchars($course['students']); ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="button" class="edit-btn" onclick="toggleDelete(<?php echo $index; ?>)">Edit</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="delete-btn" id="delete-<?php echo $index; ?>">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <span>Showing data <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalCourses); ?> of <?php echo $totalCourses; ?> entries</span>
                <div style="display: flex; gap: 5px;">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>">
                            <button style="background-color: <?php echo $i === $currentPage ? '#6B0007' : '#fff'; ?>; color: <?php echo $i === $currentPage ? 'white' : 'black'; ?>; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo $i; ?></button>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>