<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_section_id = $_POST['course_section_id'];
    $course_id = $_POST['course_id'];
    $section = $_POST['section'];
    $faculty_id = $_POST['faculty_id'];

    // Update the course section
    $update_section_query = "UPDATE course_sections SET 
        section = '$section', 
        course_id = '$course_id'
        WHERE course_section_id = '$course_section_id'";

    if (mysqli_query($con, $update_section_query)) {
        // Check if the faculty is already assigned to the course section
        $check_faculty_query = "SELECT * FROM faculty_courses WHERE course_section_id = '$course_section_id'";
        $result = mysqli_query($con, $check_faculty_query);

        if (mysqli_num_rows($result) > 0) {
            // If the faculty is already assigned, update it
            $update_faculty_query = "UPDATE faculty_courses SET 
                faculty_id = '$faculty_id' 
                WHERE course_section_id = '$course_section_id'";

            if (mysqli_query($con, $update_faculty_query)) {
                echo "Section and faculty course updated successfully!";
            } else {
                echo "Error updating faculty course: " . mysqli_error($con);
            }
        } else {
            // If faculty is not assigned, insert a new record into faculty_courses
            $insert_faculty_query = "INSERT INTO faculty_courses (faculty_id, course_section_id) 
                VALUES ('$faculty_id', '$course_section_id')";

            if (mysqli_query($con, $insert_faculty_query)) {
                echo "Section and faculty course updated successfully!";
            } else {
                echo "Error inserting faculty course: " . mysqli_error($con);
            }
        }
    } else {
        echo "Error updating course section: " . mysqli_error($con);
    }
}

header("Location: sections.php");
exit();
?>
