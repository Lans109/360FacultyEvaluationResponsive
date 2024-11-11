<?php
include '../../db/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $program_id = $_POST['program_id']; // Added field for program

    // Update the student's information in the database
    $query = "UPDATE students 
              SET email = '$email', first_name = '$first_name', last_name = '$last_name', program_id = '$program_id' 
              WHERE student_id = $student_id";

    if (mysqli_query($con, $query)) {
        header("Location: students.php"); // Redirect back to the students management page
    } else {
        echo "Error updating record: " . mysqli_error($con); // Display error if any
    }

    mysqli_close($con);
}
?>
