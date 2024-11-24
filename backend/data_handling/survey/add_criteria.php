<?php
include_once "../../../config.php";
include '../../db/dbconnect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $survey_id = mysqli_real_escape_string($con, $_POST['survey_id']);
    $criteria_description = mysqli_real_escape_string($con, $_POST['criteria_description']);

    // Insert the new criteria into the database
    $query = "
        INSERT INTO questions_criteria (survey_id, description) 
        VALUES ('$survey_id', '$criteria_description')
    ";

    if (mysqli_query($con, $query)) {
        // Redirect back to the survey page with success
        header("Location: view_survey.php?survey_id=$survey_id");
        exit;
    } else {
        // Error handling
        echo "Error: " . mysqli_error($con);
    }
}
?>
