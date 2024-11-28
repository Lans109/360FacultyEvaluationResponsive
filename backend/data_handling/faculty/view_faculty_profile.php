<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Get the faculty_id from the URL
$faculty_id = isset($_GET['faculty_id']) ? mysqli_real_escape_string($con, $_GET['faculty_id']) : '';

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

// Fetch faculty details
$faculty_query = "
    SELECT 
        f.faculty_id, 
        f.first_name, 
        f.last_name, 
        f.email, 
        f.phone_number, 
        d.department_code,
        d.department_name,
        d.department_id,
        f.profile_image
    FROM 
        faculty f
    JOIN 
        departments d ON d.department_id = f.department_id
    WHERE 
        f.faculty_id = '$faculty_id'";

$faculty_result = mysqli_query($con, $faculty_query);
$faculty = mysqli_fetch_assoc($faculty_result);

// Fetch courses the faculty is assigned to along with course sections
$courses_query = "
SELECT 
    cs.course_section_id, 
    c.course_code, 
    c.course_name, 
    cs.section
FROM 
    course_sections cs
JOIN 
    courses c ON cs.course_id = c.course_id
JOIN 
    faculty_courses fc ON fc.course_section_id = cs.course_section_id
WHERE
    fc.faculty_id = '$faculty_id'
";

$courses_result = mysqli_query($con, $courses_query);

// Fetch all available courses in the faculty's department
$available_courses_query = "
SELECT 
    cs.course_section_id, 
    c.course_code, 
    c.course_name, 
    cs.section
FROM 
    course_sections cs
JOIN 
    courses c ON cs.course_id = c.course_id
JOIN 
    faculty f ON f.faculty_id = '$faculty_id'
WHERE
    c.department_id = f.department_id
    AND cs.course_section_id NOT IN (SELECT course_section_id FROM faculty_courses WHERE faculty_id = '$faculty_id')
    AND cs.course_section_id NOT IN (SELECT course_section_id FROM faculty_courses WHERE faculty_id != '$faculty_id')

";

$available_courses_result = mysqli_query($con, $available_courses_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Profile</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
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
                <h1>Faculty Profile</h1>
            </div>
        </div>
        <div class="content">
            <div class="faculty-profile">
                <div class="profile-info">
                    <img class="profile-image" src="../../../<?= $faculty['profile_image'] ?>">
                    <div class="faculty-info">
                        <h3><?php echo $faculty['first_name'] . " " . $faculty['last_name']; ?></h3>
                        <div><img class="icon" src="../../../frontend/assets/icons/message.svg">
                            <p><?php echo $faculty['email']; ?></p>
                        </div>
                        <div><img class="icon" src="../../../frontend/assets/icons/call.svg">
                            <p><?php echo $faculty['phone_number']; ?></p>
                        </div>
                        <div><img class="icon" src="../../../frontend/assets/icons/department.svg">
                            <p><?php echo $faculty['department_name']; ?> - <?php echo $faculty['department_code']; ?>
                            </p>
                        </div>
                        <div>
                            <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal"
                                data-target="#addModal">
                                <img src="../../../frontend/assets/icons/add.svg">&nbsp;Assign Course&nbsp;
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="100px">Section</th>
                            <th width="150px">Course Code</th>
                            <th>Course Name</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($courses_result) > 0): ?>
                            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                <tr>
                                    <td><?php echo $course['section']; ?></td>
                                    <td><?php echo $course['course_code']; ?></td>
                                    <td><?php echo $course['course_name']; ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <form name="deleteForm" action="delete_faculty_course.php" method="GET">
                                                <!-- Hidden inputs to pass the faculty_id, course_section_id, and CSRF token -->
                                                <input type="hidden" name="faculty_id" value="<?php echo $faculty_id; ?>">
                                                <input type="hidden" name="course_section_id"
                                                    value="<?php echo $course['course_section_id']; ?>">
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
                                <td colspan="4">No courses assigned yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Assign Course to Faculty</h5>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Close">
                    </span>
                </div>
                <!-- Assign Course Form -->
                <form action="add_faculty_course.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="faculty_id" value="<?= $faculty_id ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="course_section_id">Available Courses</label>
                            <select name="course_section_id" class="form-control" required>
                                <option value="">Select Course</option>
                                <?php
                                // Loop through available courses
                                while ($course = mysqli_fetch_assoc($available_courses_result)): ?>
                                    <option value="<?php echo $course['course_section_id']; ?>">
                                        <?php echo $course['course_code'] . " - " . $course['course_name'] . " (Section: " . $course['section'] . ")"; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="save-btn">Assign Course</button>
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