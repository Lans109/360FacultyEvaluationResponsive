<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Fetch all courses with their associated department
$courses_query = "SELECT c.course_id, c.course_name, c.course_code, c.course_description, d.department_name, d.department_id
                  FROM courses c
                  LEFT JOIN departments d ON c.department_id = d.department_id";
$courses_result = mysqli_query($con, $courses_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>

    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Courses</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" data-toggle="modal"
                        data-target="#addCourseModal">Add
                        Course</button>
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
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Description</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                        <tr>
                            <td><?php echo $course['course_id']; ?></td>
                            <td><?php echo $course['course_name']; ?></td>
                            <td><?php echo $course['course_code']; ?></td>
                            <td><?php echo $course['course_description']; ?></td>
                            <td><?php echo $course['department_name'] ?: 'Not Assigned'; ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editModal<?php echo $course['course_id']; ?>"
                                        data-id="<?php echo $course['course_id']; ?>"
                                        data-name="<?php echo $course['course_name']; ?>"
                                        data-code="<?php echo $course['course_code']; ?>"
                                        data-description="<?php echo $course['course_description']; ?>"
                                        data-department-id="<?php echo $course['department_id']; ?>"><i
                                            class="fa fa-edit"></i></button>

                                    <a href="delete_course.php?course_id=<?php echo $course['course_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this course?')"><i
                                            class="fa fa-trash"></i></a>
                                </div>

                            </td>
                        </tr>
                        <!-- Mobile view cards -->
                        <?php
                        echo "<div class='table-to-cards hidden'>
                                <div class='ttc-course_id'>{$course['course_id']}</div>
                                <div class='ttc-course_code'>{$course['course_code']}</div>
                                <div class='ttc-course_name'>{$course['course_name']}</div>
                                <div class='ttc-course_description'>{$course['course_description']}</div>
                                <div class='ttc-course_department'>{$course['department_name']}</div>
                                <div class='ttc_btn-edit_course'>
                                    <button class='edit-btn' data-toggle='modal'
                                        data-target='#editModal{$course['course_id']}'
                                        data-id='{$course['course_id']}'
                                        data-name='{$course['course_name']}'
                                        data-code='{$course['course_code']}'
                                        data-description='{$course['course_description']}'
                                        data-department-id='{$course['department_id']}'>Edit</button>
                                </div>
                                <div class='ttc_btn-delete_course'>
                                        <a href='delete_course.php?course_id={$course['course_id']}'
                                            class='delete-btn'
                                            onclick='return confirm(\"Are you sure you want to delete this course?\")'>Delete</a>
                                </div>
                            </div>";
                        ?>


                        <!-- Edit Course Modal -->
                        <div class="modal" id="editModal<?php echo $course['course_id']; ?>" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                                        <span class="close" class="close" data-dismiss="modal"
                                            aria-label="Close">&times;</span>
                                    </div>
                                    <form id="editForm<?php echo $course['course_id']; ?>" method="POST"
                                        action="update_course.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="course_id"
                                                value="<?php echo $course['course_id']; ?>">
                                            <div class="form-group">
                                                <label for="edit_course_name">Course Name</label>
                                                <input type="text" name="course_name" class="form-control"
                                                    value="<?php echo $course['course_name']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_course_code">Course Code</label>
                                                <input type="text" name="course_code" class="form-control"
                                                    value="<?php echo $course['course_code']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_course_description">Description</label>
                                                <textarea name="course_description" class="form-control"
                                                    required><?php echo $course['course_description']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_department_id">Department</label>
                                                <select name="department_id" class="form-control" id="edit_department_id">
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    // Fetch all departments
                                                    $departments_query = "SELECT department_id, department_name FROM departments";
                                                    $departments_result = mysqli_query($con, $departments_query);

                                                    while ($department = mysqli_fetch_assoc($departments_result)) {
                                                        $selected = ($department['department_id'] == $course['department_id']) ? 'selected' : '';
                                                        echo "<option value='" . $department['department_id'] . "' $selected>" . $department['department_name'] . "</option>";
                                                    }
                                                    ?>
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

                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <!-- <div class="pagination">
                <button>1</button>
                <button>2</button>
                <button>3</button>
            </div> -->
        </div>
    </main>

    <!-- Add Course Modal -->
    <div class="modal" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form action="add_course.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="course_name">Course Name</label>
                            <input type="text" name="course_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="course_code">Course Code</label>
                            <input type="text" name="course_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="course_description">Course Description</label>
                            <textarea name="course_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department</label>
                            <select name="department_id" class="form-control">
                                <option value="">Select Department</option>
                                <?php
                                // Fetch all departments for new course
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
                        <button type="submit" class="save-btn">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>