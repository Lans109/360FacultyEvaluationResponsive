<?php
// Include database connection
include_once "../../../config.php";

include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $question_code = mysqli_real_escape_string($con, $_POST['question_code']);
    $question_text = mysqli_real_escape_string($con, $_POST['question_text']);
    $criteria_id = mysqli_real_escape_string($con, $_POST['criteria']);
    $survey_id = mysqli_real_escape_string($con, $_POST['survey_id']); // Assuming survey_id is passed in the modal form

    // Insert the new question into the database
    $query = "INSERT INTO questions (question_code, question_text, criteria_id, survey_id) 
              VALUES ('$question_code', '$question_text', '$criteria_id', '$survey_id')";
    
    if (mysqli_query($con, $query)) {
        // Redirect back to the previous page (or a success page)
        header("Location: survey.php?success=true");
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
