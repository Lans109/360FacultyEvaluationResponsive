<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Fetch all students with their program names
$students_query = "
    SELECT s.student_id, s.email, s.first_name, s.last_name, p.program_id, p.program_code
    FROM students s 
    JOIN programs p ON s.program_id = p.program_id"; // Join to get program name
$students_result = mysqli_query($con, $students_query);

// Fetch all programs for the dropdown
$programs_query = "SELECT program_id, program_code FROM programs"; // Assuming you have a programs table
$programs_result = mysqli_query($con, $programs_query);
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
</head>

<body>

    <?php include '../../../frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <h1>Student Management</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button class="add-btn" data-toggle="modal" data-target="#addStudentModal">Add
                        Student</button>
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
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Program</th>
                        <th width="1000px">Enrolled Sections</th>
                        <th width="270px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                        <tr>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo $student['first_name']; ?></td>
                            <td><?php echo $student['last_name']; ?></td>
                            <td><?php echo $student['program_code']; ?></td>
                            <td>
                                <?php
                                // Fetch enrolled sections for the student
                                $student_id = $student['student_id'];
                                $courses_query = "
                            SELECT sec.course_section_id, c.course_name, sec.section 
                            FROM student_courses cs 
                            JOIN course_sections sec ON cs.course_section_id = sec.course_section_id 
                            JOIN courses c ON sec.course_id = c.course_id 
                            WHERE cs.student_id = $student_id";
                                $courses_result = mysqli_query($con, $courses_query);

                                if (mysqli_num_rows($courses_result) > 0) {
                                    while ($course = mysqli_fetch_assoc($courses_result)) { ?>
                                        <div class="section-row">
                                            <div class="section-details">
                                                <?php echo $course['section'] . " - " . $course['course_name']; ?>
                                            </div>
                                            <div class="section-action">
                                                <a href="delete_student_course.php?student_id=<?php echo $student_id; ?>&course_section_id=<?php echo $course['course_section_id']; ?>"
                                                    class="delete-btn"
                                                    onclick="return confirm('Are you sure you want to delete this course?')"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    <?php }
                                } else {
                                    echo "No sections enrolled.";
                                }
                                ?>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editModal<?php echo $student['student_id']; ?>"
                                        data-id="<?php echo $student['student_id']; ?>"
                                        data-first-name="<?php echo $student['first_name']; ?>"
                                        data-last-name="<?php echo $student['last_name']; ?> "
                                        data-program-id="<?php echo $student['program_id']; ?>"><i
                                            class="fa fa-edit"></i></button>

                                    <a href="delete_student.php?student_id=<?php echo $student['student_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this student?')"><i
                                            class="fa fa-trash"></i></a>

                                    <button class="enroll-btn" data-toggle="modal"
                                        data-target="#enrollCourseModal<?php echo $student['student_id']; ?>"
                                        data-program-id="<?php echo $student['program_id']; ?>">Enroll Course</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Student Modal -->
                        <div class="modal" id="editModal<?php echo $student['student_id']; ?>" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                                        <span class="close" class="close" data-dismiss="modal"
                                            aria-label="Close">&times;</span>
                                    </div>
                                    <form id="editForm<?php echo $student['student_id']; ?>" method="POST"
                                        action="update_student.php">
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
                                            <button type="submit" class="save-btn">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Enroll Course Modal -->
                        <div class="modal" id="enrollCourseModal<?php echo $student['student_id']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="enrollCourseLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="enrollCourseLabel">Enroll Course</h5>
                                        <span class="close" class="close" data-dismiss="modal"
                                            aria-label="Close">&times;</span>
                                    </div>
                                    <form method="POST" action="add_course_to_student.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="student_id"
                                                value="<?php echo $student['student_id']; ?>">
                                            <div class="form-group">
                                                <label for="course_section_id">Select Course Section</label>
                                                <select name="course_section_id" class="form-control" required>
                                                    <option value="">Select Course</option>
                                                    <?php
                                                    // Fetch sections based on the student's program
                                                    $program_id = $student['program_id'];
                                                    $program_sections_query = "
                                                SELECT sec.course_section_id, c.course_name, sec.section 
                                                FROM course_sections sec 
                                                JOIN courses c ON sec.course_id = c.course_id 
                                                JOIN program_courses pc ON c.course_id = pc.course_id 
                                                WHERE pc.program_id = $program_id"; // Assuming a program_courses linking table
                                                
                                                    $program_sections_result = mysqli_query($con, $program_sections_query);

                                                    // Check if sections exist for this program
                                                    if (mysqli_num_rows($program_sections_result) > 0) {
                                                        while ($section = mysqli_fetch_assoc($program_sections_result)) {
                                                            echo "<option value='{$section['course_section_id']}'>{$section['section']} - {$section['course_name']}</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No available sections</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                            <button type="submit" class="save-btn">Enroll</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Add Student Modal -->
    <div class="modal" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentLabel">Add Student</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form method="POST" action="add_student.php">
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
                        <button type="submit" class="save-btn">Add Student</button>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>