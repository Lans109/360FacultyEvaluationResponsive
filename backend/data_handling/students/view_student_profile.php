<?php
include_once "../../../config.php";
// Include database connection
include '../../db/dbconnect.php';

// Get the student_id from the URL
$student_id = isset($_GET['student_id']) ? mysqli_real_escape_string($con, $_GET['student_id']) : '';

// Fetch student details
$student_query = "
    SELECT 
        s.student_id, 
        s.first_name, 
        s.last_name, 
        s.email, 
        s.phone_number, 
        p.program_code,
        s.program_id,
        s.profile_image
    FROM 
        students s 
    JOIN 
        programs p ON s.program_id = p.program_id
    WHERE 
        s.student_id = '$student_id'";

$student_result = mysqli_query($con, $student_query);
$student = mysqli_fetch_assoc($student_result);

// Fetch courses the student is enrolled in along with course sections
// Fetch courses the student is enrolled in along with course sections
$courses_query = "
    SELECT 
        c.course_code, 
        c.course_name,
        cs.course_section_id,
        cs.section,
        COALESCE(CONCAT(f.first_name, ' ', f.last_name), 'No Faculty Assigned') AS faculty
    FROM 
        courses c
    JOIN 
        course_sections cs ON cs.course_id = c.course_id
    LEFT JOIN
        faculty_courses fc ON fc.course_section_id = cs.course_section_id
    LEFT JOIN
        faculty f ON f.faculty_id = fc.faculty_id
    JOIN
        student_courses sc ON sc.course_section_id = cs.course_section_id
    WHERE
        sc.student_id = '$student_id'";

$courses_result = mysqli_query($con, $courses_query);

// Fetch all available courses to add that belong to the student's program
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
        program_courses pc ON c.course_id = pc.course_id
    WHERE
        pc.program_id = '{$student['program_id']}' 
        AND cs.course_section_id NOT IN (SELECT course_section_id FROM student_courses WHERE student_id = '$student_id')";

$available_courses_result = mysqli_query($con, $available_courses_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/sidebar.php'; ?>
    
    <main>
        <div class="upperMain">
            <div><h1>Student Profile</h1></div>
        </div>
        <div class="content">
            <div class="student-profile">
                <div class="profile-info">
                <img class="profile-image" src="../../../<?= $student['profile_image'] ?>">
                    <div class="student-info">
                        <h3><?php echo $student['first_name'] . " " . $student['last_name']; ?></h3>
                        <div><img class="icon" src="../../../frontend/assets/icons/message.svg"><p><?php echo $student['email']; ?></p></div>
                        <div><img class="icon" src="../../../frontend/assets/icons/call.svg"><p><?php echo $student['phone_number']; ?></p></div>
                        <div><img class="icon" src="../../../frontend/assets/icons/course.svg"><p><?php echo $student['program_code']; ?></p></div>
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
                <th>Faculty</th>
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
                        <td><?php echo $course['faculty']; ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="delete_student_course.php?student_id=<?php echo $student_id; ?>&course_section_id=<?php echo $course['course_section_id']; ?>" class="delete-btn">
                                    <img src="../../../frontend/assets/icons/delete.svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No courses enrolled yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    </main>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
</body>

</html>
