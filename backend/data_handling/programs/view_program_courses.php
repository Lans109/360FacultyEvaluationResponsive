<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Get the program_id from the URL
$program_id = isset($_GET['program_id']) ? mysqli_real_escape_string($con, $_GET['program_id']) : '';

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

// Display Status Messages if any
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

// Fetch program details
$program_query = "
    SELECT 
        p.program_id, 
        p.program_code, 
        p.program_name, 
        p.program_description,
        d.department_name,
        d.department_code
    FROM 
        programs p
    JOIN 
        departments d ON d.department_id = p.department_id
    WHERE 
        p.program_id = '$program_id'
";
$program_result = mysqli_query($con, $program_query);
$program = mysqli_fetch_assoc($program_result);

// Fetch all courses associated with the program
$courses_query = "
    SELECT 
        c.course_id, 
        c.course_name, 
        c.course_code
    FROM 
        courses c
    JOIN 
        program_courses pc ON pc.course_id = c.course_id
    WHERE 
        pc.program_id = '$program_id'
";
$courses_result = mysqli_query($con, $courses_query);

// Fetch all available courses to add to the program
$available_courses_query = "
    SELECT 
        c.course_id, 
        c.course_code, 
        c.course_name
    FROM 
        courses c
    WHERE
        c.course_id NOT IN (SELECT course_id FROM program_courses WHERE program_id = '$program_id')
";
$available_courses_result = mysqli_query($con, $available_courses_query);

// Handle course deletion
if (isset($_GET['delete_course_id'])) {
    $course_id = mysqli_real_escape_string($con, $_GET['delete_course_id']);
    $delete_query = "DELETE FROM program_courses WHERE program_id = '$program_id' AND course_id = '$course_id'";
    mysqli_query($con, $delete_query);
    header("Location: program_courses.php?program_id=$program_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Courses</title>
    <link rel="stylesheet" href="../../../frontend/templates/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>

    <main>
        <div class="upperMain">
            <div>
                <h1>Program Courses</h1>
            </div>
        </div>
        <div class="content">
            <div class="program-profile">
                <div class="profile-info">
                    <div class="program-info">
                        <h3><?php echo $program['program_name'] . ' - ' . $program['program_code']; ?></h3>
                        <div><img class="icon" src="../../../frontend/assets/icons/survey.svg">
                            <p><?php echo $program['program_description']; ?></p>
                        </div>
                        <div><img class="icon" src="../../../frontend/assets/icons/department.svg">
                            <p><?php echo $program['department_name'] . ' - ' . $program['department_code']; ?></p>
                        </div>
                        <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal"
                            data-target="#addModal">
                            <img src="../../../frontend/assets/icons/add.svg">&nbsp;Add Course&nbsp;
                        </button>
                    </div>
                </div>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="200px">Course Code</th>
                            <th>Course Name</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($courses_result) > 0): ?>
                            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                <tr>
                                    <td><?php echo $course['course_code']; ?></td>
                                    <td><?php echo $course['course_name']; ?></td>
                                    <td>
                                        <div class="action-btns">

                                            <form name="deleteForm" action="delete_program_course.php" method="POST">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <!-- Hidden input to pass the course_id -->
                                                <input type="hidden" name="course_id"
                                                    value="<?php echo $course['course_id']; ?>">
                                                <input type="hidden" name="program_id" value="<?php echo $program_id ?>">
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
                                <td colspan="3">No courses assigned yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addCourseLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseLabel">Add Course to Program</h5>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Close">
                    </span>
                </div>
                <form method="POST" action="add_course_to_program.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="program_id" value="<?= $program_id ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="course_id">Available Courses</label>
                            <select name="course_id" class="form-control" required>
                                <option value="">Select Course</option>
                                <?php while ($course = mysqli_fetch_assoc($available_courses_result)): ?>
                                    <option value="<?php echo $course['course_id']; ?>">
                                        <?php echo $course['course_code'] . " - " . $course['course_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
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

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>