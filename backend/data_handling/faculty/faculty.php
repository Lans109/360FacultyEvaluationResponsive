<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$department_filter = isset($_GET['department_filter']) ? $_GET['department_filter'] : '';
// Fetch all faculty members along with their department IDs
$faculty_query = "
SELECT 
    f.phone_number, 
    f.faculty_id, 
    f.email, 
    f.first_name, 
    f.last_name, 
    f.department_id, 
    f.profile_image,
    d.department_code, 
    CONCAT(f.first_name, ' ', f.last_name) AS full_name,
    COUNT(fc.course_section_id) AS total_courses
FROM 
    faculty f
JOIN 
    departments d ON f.department_id = d.department_id
LEFT JOIN 
    faculty_courses fc ON f.faculty_id = fc.faculty_id  -- Assuming there's a table tracking courses taught by faculty
";

// Apply search filter if the $search variable is set
if (!empty($search)) {
    $faculty_query .= " WHERE (
        d.department_code LIKE '%$search%' OR 
        CONCAT(f.first_name, ' ', f.last_name) LIKE '%$search%' OR
        f.phone_number LIKE '%$search%'
    )";
}

// Apply department filter condition if a department is selected
if (!empty($department_filter)) {
    $faculty_query .= !empty($search) 
        ? " AND f.department_id = '$department_filter'" 
        : " WHERE f.department_id = '$department_filter'";
}

$faculty_query .= "
GROUP BY 
    f.faculty_id, f.phone_number, f.email, f.first_name, f.last_name, f.department_id, f.profile_image, d.department_code";

$faculty_result = mysqli_query($con, $faculty_query);

// Fetch the number of rows returned by the query
$num_rows = mysqli_num_rows($faculty_result);


// Fetch all course sections along with their department IDs
$courses_query = "
    SELECT cs.course_section_id, c.course_name, cs.section, d.department_id
    FROM course_sections cs 
    JOIN courses c ON cs.course_id = c.course_id 
    JOIN departments d ON c.department_id = d.department_id"; // Assuming courses also have department_id
$courses_result = mysqli_query($con, $courses_query);
$courses = [];
while ($course = mysqli_fetch_assoc($courses_result)) {
    $courses[$course['department_id']][] = $course; // Organize courses by department
}

// Fetch all departments for the add faculty modal and the edit faculty modal
$departments_query = "SELECT department_id, department_name FROM departments";
$departments_result = mysqli_query($con, $departments_query);
$departments = [];
while ($department = mysqli_fetch_assoc($departments_result)) {
    $departments[] = $department; // Store all departments
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>
    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <div><h1>Faculty Management</h1></div>
        </div>
        <div class="content">
            <div class="upperContent">
            <div>
                    <p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Faculty Member' : 'Faculty Members' ?></p>
                </div>
                <!-- Search and Filter Form -->
                <div class="search-filter">
                    <form method="GET" action="">
                        <div class="form-group">            
                        <div class="search-container">
                            <input type="text" placeholder="Search..." id="search" name="search" class="search-input">
                            <button type="submit" class="search-button">
                                <i class="fa fa-search"></i>  <!-- Magnifying Glass Icon -->
                            </button>
                        </div>
                        <div class="select-container">
                            <div class="select-wrapper">
                                <select id="department_filter" name="department_filter" class="custom-select">
                                    <option value="" selected>All Departments</option>
                                    <?php
                                    // Fetch all departments to populate the filter dropdown
                                    $departments_query = "SELECT department_id, department_code FROM departments";
                                    $departments_result = mysqli_query($con, $departments_query);

                                    // Fetch and display department options
                                    while ($department = mysqli_fetch_assoc($departments_result)) {
                                        $selected = (isset($_GET['department_filter']) && $_GET['department_filter'] == $department['department_id']) ? 'selected' : '';
                                        echo "<option value='" . $department['department_id'] . "' . $selected>" . $department['department_code'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <i class="fa fa-chevron-down select-icon"></i>  <!-- Icon for dropdown -->
                            </div>
                        </div>
                            <button type="submit" class="fitler-btn"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                            <a href="faculty.php" class="fitler-btn"><i class="fa fa-eraser"></i> Clear</a>
                        </div>
                        
                    </form>
                </div>
                <div>
                    <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal" data-target="#addModal">
                        <img src="../../../frontend/assets/icons/add.svg">&nbsp;Faculty&nbsp;
                    </button>
                </div>
            </div>
            <!-- Table of Faculty -->
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="100px">Photo</th>
                            <th width="250px">Full Name</th>
                            <th width="250px">Email</th>
                            <th width="200px">Phone Number</th>
                            <th width="200px">Department</th>
                            <th width="150px">Faculty ID</th>
                            <th width="155px">No. of Courses</th>
                            <th width="100px">Profile</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($faculty_result) > 0): ?>
                        <?php while ($faculty = mysqli_fetch_assoc($faculty_result)): ?>
                            <tr>
                                <td><img class="profile-icon" src="../../../<?= $faculty['profile_image'] ?>"></td>
                                <td><?php echo $faculty['full_name']; ?></td>
                                <td><?php echo $faculty['email']; ?></td>
                                <td><?php echo $faculty['phone_number']; ?></td>
                                <td><?php echo $faculty['department_code']; ?></td>
                                <td><?php echo $faculty['faculty_id']; ?></td>
                                <td><?php echo $faculty['total_courses']; ?></td>
                                <td>
                                        <!-- View Profile Button -->
                                        <a href="view_faculty_profile.php?faculty_id=<?php echo $faculty['faculty_id']; ?>" class="view-btn">
                                            View Profile
                                        </a>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="edit-btn" data-toggle="modal"
                                            data-target="#editModal<?php echo $faculty['faculty_id']; ?>"
                                            data-id="<?php echo $faculty['faculty_id']; ?>"
                                            data-first-name="<?php echo $faculty['first_name']; ?>"
                                            data-last-name="<?php echo $faculty['last_name']; ?>"
                                            data-department-id="<?php echo $faculty['department_id']; ?>">
                                            <img src="../../../frontend/assets/icons/edit.svg"></button>

                                        <a href="delete_faculty.php?faculty_id=<?php echo $faculty['faculty_id']; ?>"
                                            class="delete-btn"
                                            onclick="openDeleteConfirmationModal(event, this)">
                                            <img src="../../../frontend/assets/icons/delete.svg"></a>

                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Faculty Modal -->
                            <div class="modal" id="editModal<?php echo $faculty['faculty_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Faculty</h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form id="editForm" method="POST" action="update_faculty.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="faculty_id"
                                                    value="<?php echo $faculty['faculty_id']; ?>">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" name="first_name" class="form-control"
                                                        value="<?php echo $faculty['first_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" name="last_name" class="form-control"
                                                        value="<?php echo $faculty['last_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="<?php echo $faculty['email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="department_id">Select Department</label>
                                                    <select name="department_id" class="form-control" required>
                                                        <option value="">Choose a department...</option>
                                                        <?php foreach ($departments as $department): ?>
                                                            <option value="<?php echo $department['department_id']; ?>" <?php echo ($department['department_id'] == $faculty['department_id']) ? 'selected' : ''; ?>>
                                                                <?php echo $department['department_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn" id="openConfirmationModalBtn">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Assign Course Modal -->
                            <div class="modal" id="assignCourseModal<?php echo $faculty['faculty_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="assignCourseLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="assignCourseLabel">Assign Course to
                                                <?php echo $faculty['first_name'] . ' ' . $faculty['last_name']; ?>
                                            </h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form method="POST" action="add_course_to_faculty.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="faculty_id"
                                                    value="<?php echo $faculty['faculty_id']; ?>">
                                                <div class="form-group">
                                                    <label for="course_section_id">Select Course Section</label>
                                                    <select name="course_section_id" class="form-control" required>
                                                        <option value="">Choose a course...</option>
                                                        <?php foreach ($courses as $department_id => $course_list): ?>
                                                            <optgroup label="Department <?php echo $department_id; ?>">
                                                                <?php foreach ($course_list as $course): ?>
                                                                    <option value="<?php echo $course['course_section_id']; ?>">
                                                                        <?php echo $course['section'] . " - " . $course['course_name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn">Assign Course</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                                <tr>
                                    <td colspan="4">No faculty members found.</td>
                                </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Add Faculty Modal -->
    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFacultyLabel">Add Faculty</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form method="POST" action="add_faculty.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Select Department</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Choose a department...</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['department_id']; ?>">
                                        <?php echo $department['department_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="save-btn">Add Faculty</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- jQuery, Bootstrap JS -->
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>