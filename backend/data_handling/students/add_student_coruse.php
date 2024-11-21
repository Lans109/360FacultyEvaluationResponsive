<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the student ID and course section ID from the POST request
    $student_id = $_POST['student_id'];
    $course_section_id = $_POST['course_section_id'];

    // Validate input
    if (!empty($student_id) && !empty($course_section_id)) {
        // Prepare the SQL statement to insert the enrollment
        $enroll_query = "
            INSERT INTO student_courses (student_id, course_section_id) 
            VALUES ($student_id, $course_section_id)";

        // Execute the query
        if (mysqli_query($con, $enroll_query)) {
            // Successful enrollment
            header("Location: view_student_profile.php?student_id=$student_id");
            exit();
        } else {
            // Failed to enroll
            header("Location: view_student_profile.php?student_id=$student_id");
            exit();
        }
    } else {
        // Invalid input
        header("Location: view_student_profile.php?student_id=$student_id");
        exit();
    }
}

// Close the database connection
mysqli_close($con);
?>
