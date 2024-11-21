<?php
include '../../db/dbconnect.php';

if (isset($_GET['student_id']) && isset($_GET['course_section_id'])) {
    $student_id = $_GET['student_id'];
    $course_section_id = $_GET['course_section_id'];

    // Prepare and execute the deletion query
    $delete_query = "DELETE FROM student_courses WHERE student_id = ? AND course_section_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("ii", $student_id, $course_section_id);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: view_student_profile.php?student_id=$student_id");
        exit();
    } else {
        // Redirect back with error message
        header("Location: view_student_profile.php?student_id=$student_id");
        exit();
    }
} else {
    // Invalid parameters
    header("Location: view_student_profile.php?student_id=$student_id");
    exit();
}
?>
