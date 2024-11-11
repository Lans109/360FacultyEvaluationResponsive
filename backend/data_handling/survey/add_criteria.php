<?php
// Include database connection
include_once "../../../config.php";

include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input
    $description = mysqli_real_escape_string($con, $_POST['description']);

    // Insert the new criteria into the database
    $query = "INSERT INTO questions_criteria (description) VALUES ('$description')";
    
    if (mysqli_query($con, $query)) {
        // Redirect back to the previous page (or a success page)
        header("Location: survey.php?success=true");
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
