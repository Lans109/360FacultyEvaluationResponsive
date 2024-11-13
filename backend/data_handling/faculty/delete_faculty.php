<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the faculty_id is set in the URL
if (isset($_GET['faculty_id'])) {
    $faculty_id = $_GET['faculty_id'];

    // Prepare a delete query
    $delete_query = "DELETE FROM faculty WHERE faculty_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    
    // Bind the faculty_id parameter
    mysqli_stmt_bind_param($stmt, 'i', $faculty_id);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Optionally, delete from faculty_courses if they exist
        $delete_courses_query = "DELETE FROM faculty_courses WHERE faculty_id = ?";
        $stmt_courses = mysqli_prepare($con, $delete_courses_query);
        mysqli_stmt_bind_param($stmt_courses, 'i', $faculty_id);
        mysqli_stmt_execute($stmt_courses);

        // Redirect back to the faculty management page with a success message
        header("Location: faculty.php?message=Faculty member deleted successfully.");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: faculty.php?error=Error deleting faculty member.");
        exit();
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($con);
?>
