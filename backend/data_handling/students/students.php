<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

// Display status messages if any
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

// Handle search and program filter inputs from the URL (GET)
if (isset($_GET['search'])) {
    $_SESSION['search'] = mysqli_real_escape_string($con, $_GET['search']);
}
if (isset($_GET['program_filter'])) {
    $_SESSION['program_filter'] = mysqli_real_escape_string($con, $_GET['program_filter']);
}

// Use session values if set, otherwise default to empty
$search = $_SESSION['search'] ?? '';
$program_filter = $_SESSION['program_filter'] ?? '';

// Query to fetch all students along with their program info and total courses
$students_query = "
    SELECT 
        s.student_id, 
        s.email, 
        CONCAT(s.first_name, ' ', s.last_name) AS full_name, 
        p.program_id, 
        p.program_code, 
        s.phone_number, 
        s.first_name, 
        s.last_name,
        s.profile_image,
        COUNT(sc.student_id) AS total_courses
    FROM 
        students s
    LEFT JOIN 
        student_courses sc ON s.student_id = sc.student_id
    JOIN 
        programs p ON s.program_id = p.program_id
";

// Apply search filter if the $search variable is set
if (!empty($search)) {
    $students_query .= " WHERE (
        p.program_code LIKE '%$search%' OR 
        CONCAT(s.first_name, ' ', s.last_name) LIKE '%$search%' OR
        s.phone_number LIKE '%$search%'
    )";
}

// Apply program filter condition if a program is selected
if (!empty($program_filter)) {
    $students_query .= !empty($search)
        ? " AND s.program_id = '$program_filter'"
        : " WHERE s.program_id = '$program_filter'";
}

$students_query .= "
GROUP BY
    s.student_id, s.email, s.first_name, s.last_name, p.program_id, p.program_code, s.phone_number, s.profile_image
";

// Execute the students query
$students_result = mysqli_query($con, $students_query);
$num_rows = mysqli_num_rows($students_result);

// Fetch all programs for the dropdown
$programs_query = "SELECT program_id, program_code FROM programs";
$programs_result = mysqli_query($con, $programs_query);
$programs = [];
while ($program = mysqli_fetch_assoc($programs_result)) {
    $programs[] = $program; // Store all programs
}

// Reset filters if needed
if (isset($_GET['reset_filters'])) {
    unset($_SESSION['search']);
    unset($_SESSION['program_filter']);
    header("Location: students.php"); // Redirect to reset the filters
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
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
                <h1>Student Management</h1>
            </div>
        </div>
        <div class="content">
            <div class="upperContent">
                <div>
                    <p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Student' : 'Students' ?></p>
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
                                    <select id="program_filter" name="program_filter" class="custom-select">
                                        <option value="" selected>All programs</option>
                                        <?php
                                        // Fetch all programs to populate the filter dropdown
                                        $programs_query = "SELECT program_id, program_code FROM programs";
                                        $programs_result = mysqli_query($con, $programs_query);

                                        // Fetch and display program options
                                        while ($program = mysqli_fetch_assoc($programs_result)) {
                                            $selected = (isset($_GET['program_filter']) && $_GET['program_filter'] == $program['program_id']) ? 'selected' : '';
                                            echo "<option value='" . $program['program_id'] . "' . $selected>" . $program['program_code'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <i class="fa fa-chevron-down select-icon"></i> <!-- Icon for dropdown -->
                                </div>
                            </div>
                            <button type="submit" class="fitler-btn"><i class="fa fa-filter" aria-hidden="true"></i>
                                Filter</button>
                            <a href="students.php?reset_filters=1" class="fitler-btn"><i class="fa fa-eraser"></i>
                                Clear</a>

                        </div>

                    </form>
                </div>
                <div>
                    <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal" data-target="#addModal">
                        <img src="../../../frontend/assets/icons/add.svg">&nbsp;Student&nbsp;
                    </button>
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
            <!-- Table of Students -->
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="100px">Photo</th>
                            <th width="250px">Full Name</th>
                            <th width="250px">Email</th>
                            <th width="200px">Phone Number</th>
                            <th width="200px">Program</th>
                            <th width="150px">Student ID</th>
                            <th width="155px">No. of Courses</th>
                            <th width="100px">Profile</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($students_result) > 0): ?>
                            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                <tr>
                                    <td><img class="profile-icon" src="../../../<?= $student['profile_image'] ?>"></td>
                                    <td><?php echo $student['full_name']; ?></td>
                                    <td><?php echo $student['email']; ?></td>
                                    <td><?php echo $student['phone_number']; ?></td>
                                    <td><?php echo $student['program_code']; ?></td>
                                    <td><?php echo $student['student_id']; ?></td>
                                    <td><?php echo $student['total_courses']; ?></td>
                                    <td>
                                        <!-- View Profile Button -->
                                        <a href="view_student_profile.php?student_id=<?php echo $student['student_id']; ?>"
                                            class="view-btn">
                                            View Profile
                                        </a>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="edit-btn" data-toggle="modal"
                                                data-target="#editModal<?php echo $student['student_id']; ?>"
                                                data-id="<?php echo $student['student_id']; ?>"
                                                data-first-name="<?php echo $student['first_name']; ?>"
                                                data-last-name="<?php echo $student['last_name']; ?> "
                                                data-program-id="<?php echo $student['program_id']; ?>">

                                                <img src="../../../frontend/assets/icons/edit.svg"></button>

                                            <form name="deleteForm" action="delete_student.php" method="POST">
                                                <input type="hidden" name="student_id"
                                                    value="<?php echo $student['student_id']; ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="delete-btn">
                                                    <img src="../../../frontend/assets/icons/delete.svg" alt="Delete Icon">
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Student Modal -->
                                <div class="modal" id="editModal<?php echo $student['student_id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                                                <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                                                    <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                                                </span>
                                            </div>
                                            <form id="editForm<?php echo $student['student_id']; ?>" method="POST"
                                                action="update_student.php">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <div class="modal-body">
                                                    <input type="hidden" name="student_id"
                                                        value="<?php echo $student['student_id']; ?>">
                                                    <div class="form-group">
                                                        <label for="edit_email">Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="<?php echo $student['email']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_first_name">First Name</label>
                                                        <input type="text" name="first_name" class="form-control"
                                                            value="<?php echo $student['first_name']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_last_name">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control"
                                                            value="<?php echo $student['last_name']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_first_name">Phone Number</label>
                                                        <input type="text" name="phone_number" class="form-control"
                                                            value="<?php echo $student['phone_number']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_first_name">Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="<?php echo $student['email']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="program_id">Program</label>
                                                        <select name="program_id" class="form-control" required>
                                                            <option value="">Select Program</option>
                                                            <?php
                                                            // Reset pointer to fetch programs again
                                                            mysqli_data_seek($programs_result, 0);
                                                            while ($program = mysqli_fetch_assoc($programs_result)):
                                                                $selected = ($program['program_id'] == $student['program_id']) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?php echo $program['program_id']; ?>" <?php echo $selected; ?>><?php echo $program['program_code']; ?></option>
                                                            <?php endwhile; ?>
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
                                </div>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No Students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </main>

    <!-- Add Student Modal -->
    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentLabel">Add Student</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>
                </div>
                <form method="POST" action="add_student.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="add_username">Username</label>
                            <input type="text" name="username" class="form-control" required>
                            <!-- Added username field -->
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="program_id">Program</label>
                            <select name="program_id" class="form-control" required>
                                <option value="">Select Program</option>
                                <?php
                                // Reset pointer to fetch programs again
                                mysqli_data_seek($programs_result, 0);
                                while ($program = mysqli_fetch_assoc($programs_result)): ?>
                                    <option value="<?php echo $program['program_id']; ?>">
                                        <?php echo $program['program_code']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="save-btn">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>