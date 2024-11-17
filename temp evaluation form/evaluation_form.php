<?php
// Database connection
$host = "localhost";
$username = "root";  
$password = ""; 
$dbname = "evalsystem"; 

$con = new mysqli($host, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = $_POST['faculty_id'];
    $survey_id = $_POST['survey_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
} else {
    // Redirect back to the selection form if accessed directly
    header("Location: faculty_survey_selection.php");
    exit();
}

// Fetch courses for the selected faculty member
$courses = [];
$sqlCourses = "SELECT cs.course_section_id, c.course_code, cs.section FROM faculty_courses fc
               JOIN course_sections cs ON fc.course_section_id = cs.course_section_id
               JOIN courses c ON cs.course_id = c.course_id
               WHERE fc.faculty_id = ?";
$stmt = $con->prepare($sqlCourses);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$resultCourses = $stmt->get_result();
if ($resultCourses->num_rows > 0) {
    while ($course = $resultCourses->fetch_assoc()) {
        $courses[] = $course;
    }
}

// Fetch questions for the selected survey
$questions = [];
$sqlQuestions = "SELECT question_id, question_code, question_text FROM questions WHERE survey_id = ?";
$stmt = $con->prepare($sqlQuestions);
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$resultQuestions = $stmt->get_result();
if ($resultQuestions->num_rows > 0) {
    while ($question = $resultQuestions->fetch_assoc()) {
        $questions[] = $question;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Form</title>
    
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        box-sizing: border-box;
    }

    h1 {
        text-align: center;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 15px;
    }

    label {
        font-size: 1rem;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    select, input[type="submit"] {
        padding: 8px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    select:focus, input[type="submit"]:focus {
        border-color: #0066cc;
    }

    input[type="submit"] {
        background-color: #0066cc;
        color: #fff;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #005bb5;
    }

    .rating {
        display: flex;
        justify-content: center; /* Center the radio buttons */
        gap: 10px;
        margin-bottom: 10px;
    }

    .rating input {
        width: auto;
    }

    .rating label {
        font-size: 1rem;
        margin-right: 5px;
    }

    .course-select, .questions {
        margin-bottom: 20px;
    }

    .questions {
        margin-top: 20px;
    }

    .question-container {
        margin-bottom: 15px;
    }

    .question-container label {
        font-size: 0.9rem;
        margin-bottom: 10px;
        display: block; /* Ensures the question text is on a new line */
    }

    .question-container .rating {
        gap: 10px;
    }

    /* Prevent text overflow and make the form fit within the page */
    .container {
        max-height: 90vh;
        overflow-y: auto;
    }
</style>

<div class="container">
    <h1>Evaluation Form for <?= htmlspecialchars($faculty_id); ?> (Survey ID: <?= htmlspecialchars($survey_id); ?>)</h1>
    <?php echo $date . " " . $time ?>
    <form action="submit_evaluation.php" method="post">
        <input type="hidden" name="faculty_id" value="<?= htmlspecialchars($faculty_id); ?>">
        <input type="hidden" name="survey_id" value="<?= htmlspecialchars($survey_id); ?>">
        <input type="hidden" name="date" value="<?= htmlspecialchars($date); ?>">
        <input type="hidden" name="time" value="<?= htmlspecialchars($time); ?>">

        <label for="course">Course:</label>
        <select name="course_section_id" id="course" required>
            <option value="">Select a course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['course_section_id']; ?>"><?= $course['course_code'] . ' - ' . $course['section']; ?></option>
            <?php endforeach; ?>
        </select>

        <h3>Evaluation Questions:</h3>
        <?php
        foreach ($questions as $question) {
            echo '<div class="question-container">';
            echo '<label>' . htmlspecialchars($question['question_code']) . ': ' . htmlspecialchars($question['question_text']) . '</label>';
            echo '<div class="rating">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<label>';
                echo '<input type="radio" name="ratings[' . $question['question_id'] . ']" value="' . $i . '" required>';
                echo $i;
                echo '</label>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
        
        <input type="submit" value="Submit Evaluation">
    </form>
</div>