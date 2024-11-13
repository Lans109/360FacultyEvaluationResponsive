<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $question_id = mysqli_real_escape_string($con, $_POST['question_id']);
    $question_code = mysqli_real_escape_string($con, $_POST['question_code']);
    $question_text = mysqli_real_escape_string($con, $_POST['question_text']);
    $criteria_id = mysqli_real_escape_string($con, $_POST['criteria']);
    
    // Check if question_id is valid
    if (empty($question_id) || empty($question_code) || empty($question_text) || empty($criteria_id)) {
        echo "All fields are required.";
        exit();
    }

    // Prepare the UPDATE query
    $query = "UPDATE questions SET 
                question_code = '$question_code', 
                question_text = '$question_text', 
                criteria_id = '$criteria_id' 
              WHERE question_id = '$question_id'";

    // Execute the query and check for errors
    if (mysqli_query($con, $query)) {
        // Redirect back to the survey page with success message
        header("Location: survey.php?success=true");
    } else {
        echo "Error updating question: " . mysqli_error($con);
    }
} else {
    // If not POST request, redirect back to the survey page
    header("Location: survey.php");
}
?>
