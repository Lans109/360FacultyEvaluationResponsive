<?php
// Include the database connection
include_once "../../../config.php";

include ROOT_PATH . '/backend/db/dbconnect.php';

// Check if question_id is provided in the URL (through GET)
if (isset($_POST['question_id'])) {
    // Get the question ID from the URL
    $question_id = $_POST['question_id'];

    // Validate the input (ensure it's a number)
    if (!is_numeric($question_id)) {
        echo "Invalid question ID.";
        exit;
    }

    // Escape the question_id to prevent SQL injection
    $question_id = mysqli_real_escape_string($con, $question_id);

    // Prepare the SQL query to delete the question
    $sql = "DELETE FROM questions WHERE question_id = '$question_id'";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        // If deletion is successful, redirect to the question management page with a success message
        header("Location: survey.php?message=Question deleted successfully");
        exit();
    } else {
        // If deletion fails, display an error message
        echo "Error deleting question: " . mysqli_error($con);
    }
} else {
    // If no question_id is provided, show an error
    echo "No question ID specified.";
}

// Close the database connection
mysqli_close($con);
?>
