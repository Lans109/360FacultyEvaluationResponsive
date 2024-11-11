<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the faculty ID and course section ID from the POST request
    $faculty_id = $_POST['faculty_id'];
    $course_section_id = $_POST['course_section_id'];

    // Validate input
    if (!empty($faculty_id) && !empty($course_section_id)) {
        // Prepare the SQL statement to insert into faculty_courses
        $query = "
            INSERT INTO faculty_courses (faculty_id, course_section_id) 
            VALUES ($faculty_id, $course_section_id)";

        // Execute the query
        if (mysqli_query($con, $query)) {
            echo "Course assigned successfully.";
        } else {
            echo "Error assigning course: " . mysqli_error($con);
        }
    } else {
        echo "Please provide both faculty ID and course section ID.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
mysqli_close($con);

// Redirect back to the faculty management page (adjust the URL as needed)
header("Location: faculty.php");
exit;
?>
