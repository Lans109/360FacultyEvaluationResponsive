<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Initialize the courses array
$courses = [];

// Fetch all courses for the dropdown
$courses_query = "SELECT course_id, course_name FROM courses";
$courses_result = mysqli_query($con, $courses_query);

// Check if the query was successful
if ($courses_result) {
    while ($course = mysqli_fetch_assoc($courses_result)) {
        $courses[$course['course_id']] = $course['course_name'];
    }
} else {
    // Handle the error if the query fails
    echo "Error fetching courses: " . mysqli_error($con);
}

// Fetch all course sections with their associated courses and count of students
// Fetch all course sections with their associated courses, faculty, and student count
$sections_query = "
    SELECT cs.course_section_id, cs.section, cs.course_id, c.course_name, f.faculty_id,
           CONCAT(f.first_name, ' ', f.last_name) as faculty_name, COUNT(sc.student_id) AS student_count
    FROM course_sections cs
    LEFT JOIN courses c ON cs.course_id = c.course_id
    LEFT JOIN faculty_courses fc ON cs.course_section_id = fc.course_section_id
    LEFT JOIN faculty f ON fc.faculty_id = f.faculty_id
    LEFT JOIN student_courses sc ON cs.course_section_id = sc.course_section_id
    GROUP BY cs.course_section_id, cs.section, c.course_name, faculty_name
    ORDER BY faculty_name";
$sections_result = mysqli_query($con, $sections_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Section Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>

    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Course Section Management</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button class="add-btn" data-toggle="modal" data-target="#addSectionModal">Add New
                        Course
                        Section</button>
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

            <!-- Table of Course Sections -->
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="150px">Section Name</th>
                            <th>Course Name</th>
                            <th width="300px">Faculty Name</th>  <!-- Added column for faculty -->
                            <th width="160px">No. of Students</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Check if there are results
                    if (mysqli_num_rows($sections_result) > 0) {
                        while ($section = mysqli_fetch_assoc($sections_result)):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($section['section']); ?></td>
                            <td><?php echo htmlspecialchars($section['course_name']); ?></td>
                            <td>
                                <?php 
                                    // Check if faculty name is empty
                                    echo !empty($section['faculty_name']) ? htmlspecialchars($section['faculty_name']) : 'No faculty assigned';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($section['student_count']); ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editModal<?php echo $section['course_section_id']; ?>"
                                        data-id="<?php echo $section['course_section_id']; ?>"
                                        data-section="<?php echo $section['section']; ?>"
                                        data-course-id="<?php echo $section['course_id']; ?>">
                                    
                                        <img src="../../../frontend/assets/icons/edit.svg"></button>

                                    <a href="delete_section.php?course_section_id=<?php echo $section['course_section_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this course section?')">
                                    
                                        <img src="../../../frontend/assets/icons/delete.svg"></a>

                                </div>
                            </td>
                        </tr>

                        <!-- Edit Section Modal -->
                        <div class="modal fade" id="editModal<?php echo $section['course_section_id']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Course Section</h5>
                                        <span class="close" class="close" data-dismiss="modal"
                                            aria-label="Close">&times;</span>
                                    </div>
                                    <form id="editForm<?php echo $section['course_section_id']; ?>" method="POST"
                                        action="update_section.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="course_section_id"
                                                value="<?php echo $section['course_section_id']; ?>">
                                            <div class="form-group">
                                                <label for="edit_section">Section Name</label>
                                                <input type="text" name="section" class="form-control"
                                                    value="<?php echo htmlspecialchars($section['section']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_course_id">Course</label>
                                                <select name="course_id" class="form-control" required>
                                                    <option value="">Select Course</option>
                                                    <?php foreach ($courses as $course_id => $course_name): ?>
                                                        <option value="<?php echo $course_id; ?>" <?php echo ($course_id == $section['course_id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($course_name); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_faculty_id">Faculty</label>
                                                <select name="faculty_id" class="form-control" required>
                                                    <option value="">Select Faculty</option>
                                                    <?php
                                                    // Fetch faculty for dropdown
                                                    $faculty_query = "SELECT faculty_id, CONCAT(first_name, ' ', last_name) as faculty_name FROM faculty";
                                                    $faculty_result = mysqli_query($con, $faculty_query);
                                                    while ($faculty = mysqli_fetch_assoc($faculty_result)):
                                                    ?>
                                                        <option value="<?php echo $faculty['faculty_id']; ?>" <?php echo ($faculty['faculty_id'] == $section['faculty_id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($faculty['faculty_name']); ?>
                                                        </option>
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

                    <?php endwhile; ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">No course sections found</td>
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
    </main>

    <!-- Add Course Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSectionModalLabel">Add New Course Section</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <form action="add_section.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="section">Section Name</label>
                            <input type="text" name="section" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="course_id">Course</label>
                            <select name="course_id" class="form-control" required>
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $course_id => $course_name): ?>
                                    <option value="<?php echo $course_id; ?>">
                                        <?php echo htmlspecialchars($course_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_section" class="save-btn">Add Course
                            Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>