<?php
// Start the session
session_start();

// Include the database connection file
include '../../db/dbconnect.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Sanitize the form data to prevent SQL injection
        $course_section_id = mysqli_real_escape_string($con, $_POST['course_section_id']);
        $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
        $section = mysqli_real_escape_string($con, $_POST['section']);
        $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);

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
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'Section and faculty updated successfully!';
                } else {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Error updating faculty course: ' . mysqli_error($con);
                }
            } else {
                // If faculty is not assigned, insert a new record into faculty_courses
                $insert_faculty_query = "INSERT INTO faculty_courses (faculty_id, course_section_id) 
                    VALUES ('$faculty_id', '$course_section_id')";

                if (mysqli_query($con, $insert_faculty_query)) {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'Section and faculty updated successfully!';
                } else {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Error inserting faculty course: ' . mysqli_error($con);
                }
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error updating course section: ' . mysqli_error($con);
        }

        // Redirect back to the sections page
        header("Location: sections.php");
        exit();

    } else {
        // If CSRF token doesn't match, set error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: sections.php");
        exit();
    }

} else {
    // If no POST request is made, set error message and redirect
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: sections.php");
    exit();
}
?>
