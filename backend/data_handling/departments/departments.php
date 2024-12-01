<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

### Generate a CSRF token if one doesn't exist ###
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

### Display Status Messages ###
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

### Handle Search and Filter Inputs ###
if (isset($_GET['search'])) {
    $_SESSION['search'] = mysqli_real_escape_string($con, $_GET['search']);
}
if (isset($_GET['department_filter'])) {
    $_SESSION['department_filter'] = $_GET['department_filter'];
}

// Use session values if set, otherwise default to empty
$search = $_SESSION['search'] ?? '';
$department_filter = $_SESSION['department_filter'] ?? '';

### Build Query to Fetch Departments ###
$department_query = "
    SELECT 
        d.*, 
        pc.chair_id,
        pc.first_name,
        pc.last_name
    FROM departments d
    LEFT JOIN program_chairs pc ON pc.department_id = d.department_id
";

// Add conditions for search and department filter
if ($search) {
    $department_query .= " WHERE (
        d.department_name LIKE '%$search%' OR 
        d.department_code LIKE '%$search%' OR 
        CONCAT(pc.first_name, ' ', pc.last_name) LIKE '%$search%'
    )";
}
if ($department_filter) {
    $department_query .= $search
        ? " AND d.department_id = '$department_filter'"
        : " WHERE d.department_id = '$department_filter'";
}

// Execute the query
$department_result = mysqli_query($con, $department_query);

if (!$department_result) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = "Error fetching departments: " . mysqli_error($con);
    header("Location: error_page.php");
    exit();
}

$num_rows = mysqli_num_rows($department_result);

### Reset Filters ###
if (isset($_GET['reset_filters'])) {
    unset($_SESSION['search']);
    unset($_SESSION['department_filter']);
    header("Location: departments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>
</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <div>
                <h1>Department Management</h1>
            </div>
        </div>
        <div class="content">
            <div class="upperContent">
                <p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Department' : 'Departments' ?></p>
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
                            <a href="departments.php?reset_filters=1" class="fitler-btn"><i class="fa fa-eraser"></i>
                                Clear</a>

                        </div>
                    </form>
                </div>
                <div>
                    <button id="openModalBtn-add-department" class="add-btn" data-toggle="modal"
                        data-target="#addModal">
                        <img src="../../../frontend/assets/icons/add.svg">&nbsp;Department&nbsp;
                    </button>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="180px">Department Code</th>
                            <th width="300px">Department Name</th>
                            <th>Description</th>
                            <th width="200px">Program Chair</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($department_result) > 0): ?>
                            <?php while ($department = mysqli_fetch_assoc($department_result)): ?>
                                <tr>
                                    <td><?php echo $department['department_code']; ?></td>
                                    <td><?php echo $department['department_name']; ?></td>
                                    <td><?php echo $department['department_description']; ?></td>
                                    <td>
                                        <?php
                                        echo $department['first_name'] ? $department['first_name'] . ' ' . $department['last_name'] : 'Not Assigned';
                                        ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="edit-btn" data-toggle="modal" data-target="#editModal"
                                                data-id="<?php echo $department['department_id']; ?>"
                                                data-name="<?php echo $department['department_name']; ?>"
                                                data-code="<?php echo $department['department_code']; ?>"
                                                data-description="<?php echo $department['department_description']; ?>"
                                                data-chair-id="<?php echo $department['chair_id']; ?>">
                                                <img src="../../../frontend/assets/icons/edit.svg">
                                            </button>

                                            <form name="deleteForm" action="delete_department.php" method="POST">
                                                <!-- Hidden input to pass the course_id -->
                                                <input type="hidden" name="department_id"
                                                    value="<?php echo $department['department_id']; ?>">
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
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No Departments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Department Modal -->
            <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add New Department</h5>
                            <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                            </span>
                        </div>
                        <form action="add_department.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="department_name">Department Name</label>
                                    <input type="text" name="department_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="department_code">Department Code</label>
                                    <input type="text" name="department_code" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="department_description">Department Description</label>
                                    <input type="text" name="department_description" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="chair_id">Program Chair</label>
                                    <select name="chair_id" class="form-control">
                                        <option value="">Select Program Chair</option>
                                        <?php
                                        // Fetch program chairs without assigned departments
                                        $chairs_query = "
                                    SELECT pc.chair_id, pc.first_name, pc.last_name 
                                    FROM program_chairs pc
                                    LEFT JOIN departments d ON pc.department_id = d.department_id
                                    WHERE d.department_id IS NULL";  // Only include chairs with no assigned department
                                        
                                        $chairs_result = mysqli_query($con, $chairs_query);

                                        while ($chair = mysqli_fetch_assoc($chairs_result)) {
                                            echo "<option value='" . $chair['chair_id'] . "'>" . $chair['first_name'] . " " . $chair['last_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                <button type="submit" class="save-btn">Add Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Department Modal -->
            <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                            <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                            </span>
                        </div>
                        <form id="editForm" method="POST" action="update_department.php">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="modal-body">
                                <input type="hidden" name="department_id" id="edit_department_id">
                                <div class="form-group">
                                    <label for="edit_department_name">Department Name</label>
                                    <input type="text" name="department_name" class="form-control"
                                        id="edit_department_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_department_code">Department Code</label>
                                    <input type="text" name="department_code" class="form-control"
                                        id="edit_department_code" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_department_description">Description</label>
                                    <textarea name="department_description" class="form-control"
                                        id="edit_department_description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="edit_chair_id">Program Chair</label>
                                    <select name="chair_id" class="form-control" id="edit_chair_id">
                                        <option value="">Select Program Chair</option>
                                        <?php
                                        // Fetch all program chairs
                                        $chairs_result = mysqli_query($con, $chairs_query);
                                        while ($chair = mysqli_fetch_assoc($chairs_result)) {
                                            echo "<option value='" . $chair['chair_id'] . "'>" . $chair['first_name'] . " " . $chair['last_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                <button type="submit" class="save-btn" id="openConfirmationModalBtn">Save
                                    changes</button>
                            </div>
                        </form>
                    </div>
                </div>
    </main>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var code = button.data('code');
            var description = button.data('description');
            var chairId = button.data('chair-id');

            var modal = $(this);
            modal.find('#edit_department_id').val(id);
            modal.find('#edit_department_name').val(name);
            modal.find('#edit_department_code').val(code);
            modal.find('#edit_department_description').val(description);
            modal.find('#edit_chair_id').val(chairId);
        });
    </script>

</body>

</html>