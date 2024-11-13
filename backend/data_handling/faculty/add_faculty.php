<?php
// Include database connection
include '../../db/dbconnect.php';

// Get the form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$department_id = $_POST['department_id'];

// Hash the password for security
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert the new faculty into the database
$insert_query = "
    INSERT INTO faculty (first_name, last_name, email, username, password_hash, department_id) 
    VALUES ('$first_name', '$last_name', '$email', '$username', '$password_hash', '$department_id')";

if (mysqli_query($con, $insert_query)) {
    header("Location: faculty.php?message=Faculty member added successfully.");
    exit();
} else {
    header("Location: faculty.php?error=Error deleting faculty member.");
    exit();
}

// Close the database connection
mysqli_close($con);
?>
