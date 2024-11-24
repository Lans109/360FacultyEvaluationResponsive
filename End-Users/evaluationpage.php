<?php
session_start();
include("databasecon.php");

// Ensure the user is logged in and is either a student or faculty
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['user_type'], ['students', 'faculty', 'program_chair'])) {
    header("Location: login_option.php");
    exit();
}

// Get the evaluation ID from the URL
if (!isset($_GET['evaluation_id'])) {
    header("Location: student_evaluation.php"); // Redirect if no evaluation ID
    exit();
}

$evaluation_id = $_GET['evaluation_id'];
$user_email = $_SESSION['email'];
$user_type = $_SESSION['user_type']; 

// Fetch the evaluation and its questions
$sql = "
    SELECT q.question_id, q.question_text 
    FROM questions q 
    WHERE q.survey_id = (SELECT survey_id FROM evaluations WHERE evaluation_id = ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evaluation_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_responses'])) {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Insert each response into the database
    foreach ($_POST['responses'] as $question_id => $rating) {
        // Insert response into the database
        $stmt = $conn->prepare("INSERT INTO responses (evaluation_id, question_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $evaluation_id, $question_id, $rating);
        $stmt->execute();
        $stmt->close();
    }

    // Update the evaluation status to "completed" and save the comment
    if ($user_type == 'students') {
        // For students: Update the student's evaluation status
        $stmt = $conn->prepare("UPDATE students_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND student_id = (SELECT student_id FROM students WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    } elseif ($user_type == 'faculty') {
        // For faculty: Update the faculty's evaluation status
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    }elseif ($user_type == 'program_chair') {
        // For faculty: Update the faculty's evaluation status
        $stmt = $conn->prepare("UPDATE program_chair_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND chair_id = (SELECT chair_id FROM program_chairs WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    }

    $stmt->execute();
    $stmt->close();

    // Redirect back to the appropriate evaluation page based on user type
    if ($user_type == 'students') {
        header("Location: student_evaluation.php");
    } elseif ($user_type == 'faculty') {
        header("Location: faculty_evaluation.php");
    }
    elseif ($user_type == 'program_chair') {
        header("Location: program_chair_evaluation.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <title>Evaluation</title>
    <script>
let currentQuestionIndex = 0;
let questions = <?php echo json_encode($questions); ?>;
let responses = {}; // Store responses for each question

// Function to initialize the responses object for all questions
function initializeResponses() {
    questions.forEach(question => {
        responses[question.question_id] = null; // Initialize each question response as null
    });
}

// Function to display the current question
function displayQuestion() {
    const questionContainer = document.getElementById('evaluationQuestions');
    questionContainer.innerHTML = ''; // Clear the container before adding new content

    if (questions.length > 0 && currentQuestionIndex < questions.length) {
        const question = questions[currentQuestionIndex];
        const questionHtml = `
            <div class="flashcard">
                <label>${question.question_text}</label><br>
                ${[1, 2, 3, 4, 5].map(i => `
                    <label>
                        <input type="radio" name="responses[${question.question_id}]" value="${i}" 
                            ${responses[question.question_id] == i ? 'checked' : ''} 
                            onchange="storeResponse(${question.question_id}, ${i})">
                        ${i}
                    </label>
                `).join('')}
            </div>
        `;
        questionContainer.innerHTML = questionHtml; // Display the question HTML

        // Show Next and Previous buttons
        document.getElementById('nextBtn').style.display = (currentQuestionIndex < questions.length - 1) ? 'inline-block' : 'none';
        document.getElementById('prevBtn').style.display = (currentQuestionIndex > 0) ? 'inline-block' : 'none';

        // Display comments and submit button only after the last question
        if (currentQuestionIndex === questions.length - 1) {
            document.getElementById('commentSection').style.display = 'block'; // Show comments
            document.getElementById('submitBtn').style.display = 'inline-block'; // Show submit button
        } else {
            document.getElementById('commentSection').style.display = 'none'; // Hide comments
            document.getElementById('submitBtn').style.display = 'none'; // Hide submit button
        }
    }
}

// Function to store response when a radio button is selected
function storeResponse(questionId, rating) {
    responses[questionId] = rating; // Store the response for the specific question
}

// Function to handle Next Button click
function nextQuestion() {
    const questionId = questions[currentQuestionIndex].question_id;
    const selectedRating = document.querySelector(`input[name="responses[${questionId}]"]:checked`);
    if (selectedRating) {
        responses[questionId] = selectedRating.value; // Store the response
    }

    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++;
        displayQuestion(); // Display the next question
    }
}

// Function to handle Previous Button click
function prevQuestion() {
    const questionId = questions[currentQuestionIndex].question_id;
    const selectedRating = document.querySelector(`input[name="responses[${questionId}]"]:checked`);
    if (selectedRating) {
        responses[questionId] = selectedRating.value; // Store the response
    }

    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        displayQuestion(); // Display the previous question
    }
}

// Function to validate the form before submission
function validateForm() {
    const comment = document.getElementById('comment').value.trim();
    const totalQuestions = questions.length;

    // Check if all questions have been answered
    for (let i = 0; i < totalQuestions; i++) {
        const question = questions[i];
        if (!responses[question.question_id]) {
            alert("Please answer all questions before submitting.");
            return false;
        }
    }

    if (comment === "") {
        alert("Please provide a comment before submitting.");
        return false;
    }

    // Add responses to the form
    const form = document.getElementById('evaluationForm');
    Object.keys(responses).forEach(questionId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'responses[' + questionId + ']';
        input.value = responses[questionId];
        form.appendChild(input);
    });

    return true; // Form is valid
}

// Call this function when the page loads
window.onload = function() {
    initializeResponses(); // Initialize the responses object with null values
    displayQuestion(); // Load the first question
};
</script>
</head>
<body>
    <div class="header">
        <h1>Evaluation</h1>
        <nav>
        <a href="<?php 
            if ($user_type == 'students') {
                echo 'student_evaluation.php';
            } elseif ($user_type == 'faculty') {
                echo 'faculty_evaluation.php';
            } 
            elseif ($user_type == 'program_chair') {
                echo 'program_chair_evaluation.php';
            } 
        ?>" onclick="return confirm('Are you sure you want to return? Evaluation data will not be saved');">Return</a>
        </nav>
    </div>
    <div class="container">
        <div class="card">
            <h2>Evaluation Questions</h2>
            <form id="evaluationForm" method="POST" action="evaluationpage.php?evaluation_id=<?php echo $evaluation_id; ?>" onsubmit="return validateForm()">
                <div id="evaluationQuestions"></div>

                <!-- Comment Section (Initially hidden, displayed after last question) -->
                <div id="commentSection" style="display: none;">
                    <label for="comment">Additional comment about your professor: (type NA if none)</label><br>
                    <textarea name="comment" id="comment" rows="4" cols="50" maxlength="220"></textarea>
                </div>

                <div>
                    <button type="button" id="prevBtn" onclick="prevQuestion()" style="background-color: gray; color: white; display: none;">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextQuestion()" style="background-color: blue; color: white;">Next</button>
                </div>

                <!-- Submit Button (Initially hidden, displayed after last question) -->
                <input type="submit" name="submit_responses" id="submitBtn" value="Submit Responses" style="background-color: green; color: white; display: none;">
            </form>
        </div>
    </div>
</body>
</html>
