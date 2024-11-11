<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the student_id is set
if (isset($_GET['student_id'])) {
    // Get the student_id from the query string
    $student_id = $_GET['student_id'];

    // Create the delete query
    $delete_query = "DELETE FROM students WHERE student_id = $student_id";

    // Execute the query
    if (mysqli_query($con, $delete_query)) {
        // Student deleted successfully
        echo "Student deleted successfully.";
        header("Location: students.php?msg=Student removed successfully");
    } else {
        // Error executing the query
        echo "Error: Could not execute query: $delete_query. " . mysqli_error($con);
    }
} else {
    // If student_id is not set
    echo "Error: student_id not provided.";
}

// Close the connection
mysqli_close($con);

// Redirect back to the student management page (optional)
// header("Location: student_management.php"); // Uncomment this line to redirect
?>
