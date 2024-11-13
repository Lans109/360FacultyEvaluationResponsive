<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the request method is GET and the necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['faculty_id']) && isset($_GET['course_section_id'])) {
    // Get the faculty ID and course section ID from the GET request
    $faculty_id = $_GET['faculty_id'];
    $course_section_id = $_GET['course_section_id'];

    // Validate input
    if (!empty($faculty_id) && !empty($course_section_id)) {
        // Prepare the SQL statement to delete from faculty_courses
        $query = "
            DELETE FROM faculty_courses 
            WHERE faculty_id = $faculty_id AND course_section_id = $course_section_id";

        // Execute the query
        if (mysqli_query($con, $query)) {
            echo "Course removed successfully.";
        } else {
            echo "Error removing course: " . mysqli_error($con);
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
