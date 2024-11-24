<?php
include '../../db/dbconnect.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $survey_id = isset($_POST['survey_id']) ? mysqli_real_escape_string($con, $_POST['survey_id']) : '';
    $question_id = isset($_POST['question_id']) ? mysqli_real_escape_string($con, $_POST['question_id']) : '';
    $question_code = isset($_POST['question_code']) ? mysqli_real_escape_string($con, $_POST['question_code']) : '';
    $question_text = isset($_POST['question_text']) ? mysqli_real_escape_string($con, $_POST['question_text']) : '';

    // Validate inputs
    if ($survey_id && $question_id && $question_code && $question_text) {
        $update_query = "
            UPDATE questions
            SET question_code = '$question_code',
                question_text = '$question_text'
            WHERE question_id = '$question_id' AND survey_id = '$survey_id'
        ";

        if (mysqli_query($con, $update_query)) {
            header("Location: view_survey.php?survey_id=$survey_id&status=success");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Invalid data received.";
    }
}
?>
