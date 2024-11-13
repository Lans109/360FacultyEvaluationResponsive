<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the necessary GET parameters are set
if (isset($_GET['program_id']) && isset($_GET['course_id'])) {
    $program_id = intval($_GET['program_id']);
    $course_id = intval($_GET['course_id']);

    // Prepare the delete query to remove the course from the program
    $delete_query = "DELETE FROM program_courses WHERE program_id = ? AND course_id = ?";
    
    // Prepare the statement
    if ($stmt = mysqli_prepare($con, $delete_query)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ii", $program_id, $course_id);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the program management page with success message
            header("Location: programs.php?message=Course removed successfully.");
        } else {
            // Redirect back with error message
            header("Location: programs.php?error=Error removing course.");
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Redirect back with error message if prepare fails
        header("Location: programs.php?error=Error preparing statement.");
    }
} else {
    // Redirect back with error message if parameters are missing
    header("Location: programs.php?error=Invalid request.");
}

// Close the database connection
mysqli_close($con);
?>
