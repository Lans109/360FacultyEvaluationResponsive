<?php
include '../../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $survey_id = mysqli_real_escape_string($con, $_POST['survey_id']);
    $criteria_id = mysqli_real_escape_string($con, $_POST['criteria_id']);
    $question_code = mysqli_real_escape_string($con, $_POST['question_code']);
    $question_text = mysqli_real_escape_string($con, $_POST['question_text']);
    
    // Insert the new question into the database
    $insert_query = "
        INSERT INTO questions (survey_id, criteria_id, question_code, question_text)
        VALUES ('$survey_id', '$criteria_id', '$question_code', '$question_text')
    ";
    
    if (mysqli_query($con, $insert_query)) {
        // Redirect to the survey questions page with a success message
        header("Location: view_survey.php?survey_id=$survey_id&status=success");
    } else {
        // Log the error and show a user-friendly message
        error_log("Error adding question: " . mysqli_error($con));
        header("Location: view_survey.php?survey_id=$survey_id&status=error");
    }
}
?>
