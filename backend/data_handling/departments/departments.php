<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Retrieve departments and program chairs
$query = "SELECT 
                d.*, 
                pc.chair_id,
                pc.first_name,
                pc.last_name
            FROM 
                departments d
            LEFT JOIN 
                program_chairs pc ON pc.department_id = d.department_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error fetching departments: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments and Program Chairs</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>

    <?php include '../../../frontend/layout/navbar.php'; ?>
    </style>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <h1>Departments and Program Chairs</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button class="add-btn" data-toggle="modal" data-target="#addModal">Add New
                        Department</button>
                </div>

                <!-- no function yet add at app.js
                    <div class="sortDropDown">
                        <label for="sort">Sort by:</label>
                        <select id="sort" onchange="sortCourses()">
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </div> -->
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Department Code</th>
                        <th>Description</th>
                        <th>Program Chair</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['department_name']; ?></td>
                            <td><?php echo $row['department_code']; ?></td>
                            <td><?php echo $row['department_description']; ?></td>
                            <td>
                                <?php
                                echo $row['first_name'] ? $row['first_name'] . ' ' . $row['last_name'] : 'Not Assigned';
                                ?>
                            </td>
                            <td>
                                <button class="edit-btn" data-toggle="modal" data-target="#editModal"
                                    data-id="<?php echo $row['department_id']; ?>"
                                    data-name="<?php echo $row['department_name']; ?>"
                                    data-code="<?php echo $row['department_code']; ?>"
                                    data-description="<?php echo $row['department_description']; ?>"
                                    data-chair-id="<?php echo $row['chair_id']; ?>">Edit</button>
                                <button class="delete-btn">
                                    <a href="delete_department.php?department_id=<?php echo $row['department_id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Add Department Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add New Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="add_department.php" method="POST">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Add Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Department Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" method="POST" action="update_department.php">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
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