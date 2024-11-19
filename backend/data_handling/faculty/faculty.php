<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Fetch all faculty members along with their department IDs
$faculty_query = "
    SELECT f.faculty_id, f.email, f.first_name, f.last_name, f.department_id 
    FROM faculty f"; // No need to join faculty_departments anymore
$faculty_result = mysqli_query($con, $faculty_query);

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

    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <h1>Faculty Management</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button class="add-btn" data-toggle="modal" data-target="#addFacultyModal">Add
                        Faculty</button>
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
            <!-- Table of Faculty -->
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Faculty ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Department</th>
                            <th width="600px">Assigned Courses</th>
                            <th width="250px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($faculty = mysqli_fetch_assoc($faculty_result)): ?>
                            <tr>
                                <td><?php echo $faculty['faculty_id']; ?></td>
                                <td><?php echo $faculty['email']; ?></td>
                                <td><?php echo $faculty['first_name']; ?></td>
                                <td><?php echo $faculty['last_name']; ?></td>
                                <td>
                                    <?php
                                    // Fetch department name
                                    $department_id = $faculty['department_id'];
                                    $department_query = "SELECT department_name FROM departments WHERE department_id = $department_id";
                                    $department_result = mysqli_query($con, $department_query);
                                    $department = mysqli_fetch_assoc($department_result);
                                    echo $department['department_name'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Fetch assigned courses for the faculty
                                    $faculty_id = $faculty['faculty_id'];
                                    $assigned_courses_query = "
        SELECT cs.course_section_id, c.course_name, cs.section 
        FROM faculty_courses fc 
        JOIN course_sections cs ON fc.course_section_id = cs.course_section_id 
        JOIN courses c ON cs.course_id = c.course_id 
        WHERE fc.faculty_id = $faculty_id";
                                    $assigned_courses_result = mysqli_query($con, $assigned_courses_query);

                                    if (mysqli_num_rows($assigned_courses_result) > 0) {
                                        while ($course = mysqli_fetch_assoc($assigned_courses_result)) { ?>
                                            <div class="section-row">
                                                <div class="section-details">
                                                    <?php echo $course['section'] . " - " . $course['course_name']; ?>
                                                </div>
                                                <div class="section-action">
                                                    <a href="delete_faculty_course.php?faculty_id=<?php echo $faculty_id; ?>&course_section_id=<?php echo $course['course_section_id']; ?>"
                                                        class="delete-btn"
                                                        onclick="return confirm('Are you sure you want to remove this course?')"><i
                                                            class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        <?php }
                                    } else {
                                        echo "No courses assigned.";
                                    }
                                    ?>
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
                                            onclick="return confirm('Are you sure you want to delete this faculty member?')">
                                            <img src="../../../frontend/assets/icons/delete.svg"></a>

                                        <button class="table_add-btn" data-toggle="modal"
                                            data-target="#assignCourseModal<?php echo $faculty['faculty_id']; ?>">Assign
                                            Course</button>
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
                                        <form method="POST" action="update_faculty.php">
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
                                                <button type="submit" class="save-btn">Save changes</button>
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
                    </tbody>
                </table>s
            </div>
        </div>

    </main>

    <!-- Add Faculty Modal -->
    <!-- Add Faculty Modal -->
    <div class="modal" id="addFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyLabel"
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