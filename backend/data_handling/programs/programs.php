<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Initialize search and filter variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$department_filter = isset($_GET['department_filter']) ? $_GET['department_filter'] : '';

// Fetch all programs with their associated department
$programs_query = "SELECT p.program_id, p.program_name, p.program_code, p.program_description, d.department_name, p.department_id, d.department_code
                   FROM programs p
                   LEFT JOIN departments d ON p.department_id = d.department_id";

// Check if a search term is provided
if ($search) {
    $programs_query .= " WHERE (p.program_name LIKE '%$search%' OR p.program_code LIKE '%$search%')";
}

// Add department filter condition if a department is selected
if ($department_filter) {
    $programs_query .= $search ? " AND p.department_id = '$department_filter'" : " WHERE p.department_id = '$department_filter'";
}

// Execute the query
$programs_result = mysqli_query($con, $programs_query);

$num_rows = mysqli_num_rows($programs_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <div><h1>Program Management</h1></div>
        </div>
        <div class="content">

            <div class="upperContent">
                <div>
                    <p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Program' : 'Programs' ?></p>
                </div>
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
                                            echo "<option value='" . $department['department_id'] . "' $selected>" . $department['department_code'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <i class="fa fa-chevron-down select-icon"></i>  <!-- Icon for dropdown -->
                                </div>
                            </div>
                            <button type="submit" class="fitler-btn"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                            <a href="programs.php" class="fitler-btn"><i class="fa fa-eraser"></i> Clear</a>                
                        </div>
                    </form>
                </div>
                <div>
                    <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal" data-target="#addModal">
                        <img src="../../../frontend/assets/icons/add.svg">&nbsp;Program&nbsp;
                    </button>
                </div>
            </div>

            <!-- Table of Programs -->
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="160px">Program Code</th>
                            <th width="300px">Program Name</th>
                            <th>Description</th>
                            <th width="150px">Department</th>
                            <th width="100px">Courses</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($programs_result) > 0): ?>
                        <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                            <tr>
                                <td><?php echo $program['program_code']; ?></td>
                                <td><?php echo $program['program_name']; ?></td>
                                <td><?php echo $program['program_description']; ?></td>
                                <td><?php echo $program['department_code'] ?: 'Not Assigned'; ?></td>
                                <td>
                                    <!-- Add Course Button -->
                                    <button class="view-btn" data-toggle="modal"
                                        data-target="#addCourseModal<?php echo $program['program_id']; ?>">View
                                        Courses</button>

                                    <!-- Add Course Modal -->
                                    <div class="modal" id="addCourseModal<?php echo $program['program_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addCourseModalLabel">Courses of <?php echo $program['program_code']; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="add_course_to_program.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="program_id" value="<?php echo $program['program_id']; ?>">

                                                        <!-- Dropdown for courses -->
                                                        <div class="form-group">
                                                            <label for="course_id">Select a Course</label>
                                                            <div>
                                                                <select name="course_id" id="course_id" class="form-control" required>
                                                                    <option value="">-- Choose a Course --</option>
                                                                    <?php
                                                                    // Fetch all courses for the dropdown
                                                                    $all_courses_query = "SELECT course_id, course_name FROM courses";
                                                                    $all_courses_result = mysqli_query($con, $all_courses_query);
                                                                    while ($course = mysqli_fetch_assoc($all_courses_result)) {
                                                                        echo "<option value='" . $course['course_id'] . "'>" . $course['course_name'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Display existing courses -->
                                                        <div class="form-group">
                                                            <label for="existing_courses" class="form-label">Current Courses</label>
                                                            <div class="existing-courses-list">
                                                                <?php
                                                                $courses_query = "SELECT c.course_id, c.course_name 
                                                                    FROM courses c
                                                                    JOIN program_courses pc ON c.course_id = pc.course_id
                                                                    WHERE pc.program_id = " . $program['program_id'];
                                                                $courses_result = mysqli_query($con, $courses_query);

                                                                if (mysqli_num_rows($courses_result) > 0) {
                                                                    echo "<ul class='list-group'>";
                                                                    while ($course = mysqli_fetch_assoc($courses_result)) {
                                                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                                                        echo $course['course_name'];
                                                                        echo "<a href='delete_program_course.php?course_id=" . $course['course_id'] . "&program_id=" . $program['program_id'] . "' 
                                                                                class='btn btn-danger btn-sm ml-3'
                                                                               onclick='openDeleteConfirmationModal(event, this)'> Remove</a>";
                                                                        echo "</li>";
                                                                    }
                                                                    echo "</ul>";
                                                                } else {
                                                                    echo "<p class='text-muted'>No courses assigned to this program.</p>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="save-btn">Add Course</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="edit-btn" data-toggle="modal"
                                            data-target="#editModal<?php echo $program['program_id']; ?>"
                                            data-id="<?php echo $program['program_id']; ?>"
                                            data-name="<?php echo $program['program_name']; ?>"
                                            data-code="<?php echo $program['program_code']; ?>"
                                            data-description="<?php echo $program['program_description']; ?>"
                                            data-department-id="<?php echo $program['department_id']; ?>">
                                        
                                            <img src="../../../frontend/assets/icons/edit.svg"></button>

                                        <a href="delete_program.php?program_id=<?php echo $program['program_id']; ?>"
                                            class="delete-btn"
                                            onclick="openDeleteConfirmationModal(event, this)">
                                        
                                            <img src="../../../frontend/assets/icons/delete.svg"></a>

                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Program Modal -->
                            <div class="modal fade" id="editModal<?php echo $program['program_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Program</h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form method="POST" action="update_program.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="program_id"
                                                    value="<?php echo $program['program_id']; ?>">
                                                <div class="form-group">
                                                    <label for="program_name">Program Name</label>
                                                    <input type="text" name="program_name" class="form-control"
                                                        value="<?php echo $program['program_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="program_code">Program Code</label>
                                                    <input type="text" name="program_code" class="form-control"
                                                        value="<?php echo $program['program_code']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="program_description">Program Description</label>
                                                    <textarea name="program_description" class="form-control"
                                                        required><?php echo $program['program_description']; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="department_id">Department</label>
                                                    <select name="department_id" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php
                                                        // Fetch all departments for the dropdown
                                                        $departments_query = "SELECT department_id, department_name FROM departments";
                                                        $departments_result = mysqli_query($con, $departments_query);
                                                        while ($department = mysqli_fetch_assoc($departments_result)) {
                                                            $selected = ($department['department_id'] == $program['department_id']) ? 'selected' : '';
                                                            echo "<option value='" . $department['department_id'] . "' $selected>" . $department['department_name'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn" id="openConfirmationModalBtn">Update Program</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                                <tr>
                                    <td colspan="4">No Programs found.</td>
                                </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div class="pagination">
            <button>1</button>
            <button>2</button>
            <button>3</button>
        </div> -->
        </div>
    </main>

    <!-- Add Program Modal -->
    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form action="add_program.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="program_name">Program Name</label>
                            <input type="text" name="program_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="program_code">Program Code</label>
                            <input type="text" name="program_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="program_description">Program Description</label>
                            <textarea name="program_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php
                                // Fetch all departments for the dropdown
                                $departments_query = "SELECT department_id, department_name FROM departments";
                                $departments_result = mysqli_query($con, $departments_query);
                                while ($department = mysqli_fetch_assoc($departments_result)) {
                                    echo "<option value='" . $department['department_id'] . "'>" . $department['department_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="save-btn">Add Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
