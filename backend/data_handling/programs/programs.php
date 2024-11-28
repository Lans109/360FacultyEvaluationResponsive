<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Display status messages if available
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include a status handling layout for displaying messages
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status'], $_SESSION['message']);
}

// Handle search and department filter inputs
if (isset($_GET['search'])) {
    $_SESSION['search'] = mysqli_real_escape_string($con, $_GET['search']);
}
if (isset($_GET['department_filter'])) {
    $_SESSION['department_filter'] = $_GET['department_filter'];
}

// Use session values for search and filter or default to empty
$search = $_SESSION['search'] ?? '';
$department_filter = $_SESSION['department_filter'] ?? '';

// Base query to fetch programs and associated departments
$programs_query = "
    SELECT 
        p.program_id, 
        p.program_name, 
        p.program_code, 
        p.program_description, 
        d.department_name, 
        p.department_id, 
        d.department_code, 
        COUNT(pc.program_id) AS total_courses
    FROM 
        programs p
    LEFT JOIN 
        program_courses pc ON p.program_id = pc.program_id
    LEFT JOIN 
        departments d ON p.department_id = d.department_id
";

// Apply search filter
if (!empty($search)) {
    $programs_query .= " WHERE (
        p.program_name LIKE '%$search%' OR 
        p.program_code LIKE '%$search%' OR 
        d.department_name LIKE '%$search%'
    )";
}

// Apply department filter
if (!empty($department_filter)) {
    $programs_query .= !empty($search)
        ? " AND p.department_id = '$department_filter'"
        : " WHERE p.department_id = '$department_filter'";
}

// Group the query results
$programs_query .= "
    GROUP BY 
        p.program_id, p.program_name, p.program_code, 
        p.program_description, d.department_name, 
        p.department_id, d.department_code";

// Execute the query
$programs_result = mysqli_query($con, $programs_query);
$num_rows = mysqli_num_rows($programs_result);

// Reset filters if needed
if (isset($_GET['reset_filters'])) {
    unset($_SESSION['search'], $_SESSION['department_filter']);
    header("Location: programs.php"); // Adjust to the appropriate redirection URL
    exit();
}
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
            <div>
                <h1>Program Management</h1>
            </div>
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
                                <input type="text" placeholder="Search..." id="search" name="search"
                                    class="search-input">
                                <button type="submit" class="search-button">
                                    <i class="fa fa-search"></i> <!-- Magnifying Glass Icon -->
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
                                    <i class="fa fa-chevron-down select-icon"></i> <!-- Icon for dropdown -->
                                </div>
                            </div>
                            <button type="submit" class="fitler-btn"><i class="fa fa-filter" aria-hidden="true"></i>
                                Filter</button>
                            <a href="programs.php?reset_filters=1" class="fitler-btn"><i class="fa fa-eraser"></i>
                                Clear</a>
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
                            <th width="250px">Program Name</th>
                            <th width="500px">Description</th>
                            <th width="150px">Department</th>
                            <th width="150px">No. of courses</th>
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
                                    <td><?php echo $program['total_courses']; ?></td>
                                    <td>
                                        <!-- Add Course Button -->
                                        <a href="view_program_courses.php?program_id=<?php echo $program['program_id']; ?>"
                                            class="view-btn">
                                            View Courses
                                        </a>

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

                                            <form name="deleteForm" action="delete_program.php" method="POST">
                                                <!-- Hidden input to pass the course_id -->
                                                <input type="hidden" name="program_id"
                                                    value="<?php echo $program['program_id']; ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <!-- Submit button for deleting the course -->
                                                <button type="submit" class="delete-btn">
                                                    <img src="../../../frontend/assets/icons/delete.svg" alt="Delete Icon">
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Program Modal -->
                                <div class="modal" id="editModal<?php echo $program['program_id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Program</h5>
                                                <span class="close" data-dismiss="modal" aria-label="Close">
                                                    <img src="../../../frontend/assets/icons/close2.svg" alt="Close">
                                                </span>
                                            </div>
                                            <form id="editForm<?php echo $program['program_id']; ?>" method="POST"
                                                action="update_program.php">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
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
                                                    <button type="submit" class="save-btn">Update Program</button>
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
        </div>
    </main>

    <!-- Add Program Modal -->
    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>
                </div>
                <form action="add_program.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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