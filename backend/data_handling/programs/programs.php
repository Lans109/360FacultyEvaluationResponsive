<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Fetch all programs with their associated department
$programs_query = "SELECT p.program_id, p.program_name, p.program_code, p.program_description, d.department_name, p.department_id
                   FROM programs p
                   LEFT JOIN departments d ON p.department_id = d.department_id";
$programs_result = mysqli_query($con, $programs_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>

    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Program Management</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-program" class="add-btn" data-toggle="modal"
                        data-target="#addProgramModal">Add
                        Program</button>
                </div>

                <!-- no function yet add at app.js
                    <div class="sortDropDown">
                        <label for="sort">Sort by:</label>
                        <select id="sort" onchange="sortProgram()">
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </div> -->
            </div>

            <!-- Table of Programs -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Program Code</th>
                        <th>Description</th>
                        <th>Department</th>
                        <th>Courses</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <tr>
                            <td><?php echo $program['program_name']; ?></td>
                            <td><?php echo $program['program_code']; ?></td>
                            <td><?php echo $program['program_description']; ?></td>
                            <td><?php echo $program['department_name'] ?: 'Not Assigned'; ?></td>
                            <td>
                                <?php
                                // Fetch courses for the program
                                $courses_query = "SELECT c.course_id, c.course_name FROM courses c
                                          JOIN program_courses pc ON c.course_id = pc.course_id
                                          WHERE pc.program_id = " . $program['program_id'];
                                $courses_result = mysqli_query($con, $courses_query);
                                while ($course = mysqli_fetch_assoc($courses_result)) {
                                    echo $course['course_name'] . ' <a href="delete_program_course.php?program_id=' . $program['program_id'] . '&course_id=' . $course['course_id'] . '" class="text-danger" onclick="return confirm(\'Are you sure you want to remove this course?\')">[Remove]</a><br>';
                                }
                                ?>
                                <!-- Add Course Button -->
                                <button class="btn" data-toggle="modal"
                                    data-target="#addCourseModal<?php echo $program['program_id']; ?>">Add Course</button>

                                <!-- Add Course Modal -->
                                <div class="modal" id="addCourseModal<?php echo $program['program_id']; ?>" tabindex="-1"
                                    role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCourseModalLabel">Add Course to
                                                    <?php echo $program['program_name']; ?>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="add_course_to_program.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="program_id"
                                                        value="<?php echo $program['program_id']; ?>">
                                                    <div class="form-group">
                                                        <label for="course_id">Course</label>
                                                        <select name="course_id" class="form-control" required>
                                                            <option value="">Select Course</option>
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
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add Course</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="edit-btn" data-toggle="modal"
                                    data-target="#editModal<?php echo $program['program_id']; ?>"
                                    data-id="<?php echo $program['program_id']; ?>"
                                    data-name="<?php echo $program['program_name']; ?>"
                                    data-code="<?php echo $program['program_code']; ?>"
                                    data-description="<?php echo $program['program_description']; ?>"
                                    data-department-id="<?php echo $program['department_id']; ?>">Edit</button>
                                <button class="delete-btn">
                                    <a href="delete_program.php?program_id=<?php echo $program['program_id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this program?')">Delete</a>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Program Modal -->
                        <div class="modal fade" id="editModal<?php echo $program['program_id']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Program</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
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
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Program</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <button>1</button>
            <button>2</button>
            <button>3</button>
            <!-- Add more pagination as needed -->
        </div>
        </div>
    </main>

    <!-- Add Program Modal -->
    <div class="modal" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Program</button>
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