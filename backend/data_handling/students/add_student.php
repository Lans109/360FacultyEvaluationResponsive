<?php
// Include database connection
include '../../db/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = mysqli_real_escape_string($con, $_POST['username']); // New username field
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $program_id = mysqli_real_escape_string($con, $_POST['program_id']);

    // Hash the password for security
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new student into the database
    $query = "INSERT INTO students (username, email, first_name, last_name, password_hash, program_id) 
              VALUES ('$username', '$email', '$first_name', '$last_name', '$password_hash', '$program_id')";

    if (mysqli_query($con, $query)) {
        // Redirect to the student management page with a success message
        header("Location: students.php?message=Student added successfully");
        exit();
    } else {
        // Redirect with an error message
        header("Location: students.php?error=" . mysqli_error($con));
        exit();
    }
}
?>
