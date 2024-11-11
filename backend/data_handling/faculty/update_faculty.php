<?php
// Include database connection
include '../../db/dbconnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the faculty ID, first name, last name, email, and department from the POST request
    $email = $_POST['email'];
    $faculty_id = $_POST['faculty_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $faculty_department = $_POST['department_id'];

    // Validate input
    if ($faculty_id && $first_name && $last_name && $faculty_department) {
        // Update faculty information in the database
        $update_query = "UPDATE faculty SET first_name = '$first_name', last_name = '$last_name', email = '$email', department_id = '$faculty_department' WHERE faculty_id = '$faculty_id'";
        
        if (mysqli_query($con, $update_query)) {
            // Redirect with success message
            header("Location: faculty.php?success=Faculty updated successfully.");
            exit();
        } else {
            echo "Error updating faculty: " . mysqli_error($con);
        }
    } else {
        echo "Please fill all required fields.";
    }
}
?>
