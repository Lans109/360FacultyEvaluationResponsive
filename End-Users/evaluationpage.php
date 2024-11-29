<?php
// evaluationpage.php
session_start();
include("db/databasecon.php");

// Authentication and access control
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['user_type'], ['students', 'faculty', 'program_chair'])) {
    header("Location: ../index.php");
    exit();
}

// Validate evaluation ID
if (!isset($_GET['evaluation_id'])) {
    header("Location: students/student_evaluation.php");
    exit();
}

$evaluation_id = $_GET['evaluation_id'];
$user_email = $_SESSION['email'];
$user_type = $_SESSION['user_type'];

// Fetch evaluation questions
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
$stmt->close();

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_responses'])) {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Begin transaction for data integrity
    $conn->begin_transaction();

    try {
        // Insert individual responses
        $response_stmt = $conn->prepare("INSERT INTO responses (evaluation_id, question_id, rating) VALUES (?, ?, ?)");
        foreach ($_POST['responses'] as $question_id => $rating) {
            $response_stmt->bind_param("iii", $evaluation_id, $question_id, $rating);
            $response_stmt->execute();
        }
        $response_stmt->close();

        // Update evaluation status based on user type
        $status_update_sql = match($user_type) {
            'students' => "UPDATE students_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND student_id = (SELECT student_id FROM students WHERE email = ?)",
            'faculty' => "UPDATE faculty_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)",
            'program_chair' => "UPDATE program_chair_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND chair_id = (SELECT chair_id FROM program_chairs WHERE email = ?)",
            default => null
        };

        if ($status_update_sql) {
            $status_stmt = $conn->prepare($status_update_sql);
            $status_stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
            $status_stmt->execute();
            $status_stmt->close();
        }

        $conn->commit();

        // Redirect based on user type
        $redirect_urls = [
            'students' => 'students/student_evaluation.php',
            'faculty' => 'faculty/faculty_evaluation.php', 
            'program_chair' => 'program_chair/program_chair_evaluation.php'
        ];
        header("Location: " . $redirect_urls[$user_type]);
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        // Log error or handle appropriately
        die("Submission failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Styles/styles.css">
    <link rel="stylesheet" href="Styles/evaluation.css">
</head>
<body>
    <div class="header">
        <h1>Evaluation Form</h1>
    </div>
    <div class="container">
        <div class="card">
            <div class="progress-bar">
                <div id="progressBar" class="progress"></div>
            </div>
            
            <form id="evaluationForm" method="POST" onsubmit="return validateForm()">
                <div id="questionContainer">
                    <div id="questionText" class="question-text"></div>
                    <div id="optionsContainer" class="options-container"></div>
                </div>

                <div id="commentSection" style="display:none;">
                    <label for="comment">Additional Comments (Optional):</label>
                    <textarea id="comment" name="comment" placeholder="Share your thoughts..."></textarea>
                </div>

                <div class="navigation-buttons">
                    <button type="button" id="prevBtn" style="display:none;">Previous</button>
                    <button type="button" id="nextBtn">Next</button>
                    <button type="submit" id="submitBtn" name="submit_responses" style="display:none;">Submit Evaluation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const questions = <?php echo json_encode($questions); ?>;
        let currentQuestionIndex = 0;
        let responses = {};

        const questionText = document.getElementById('questionText');
        const optionsContainer = document.getElementById('optionsContainer');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const commentSection = document.getElementById('commentSection');
        const progressBar = document.getElementById('progressBar');

        function initializeResponses() {
            questions.forEach(q => responses[q.question_id] = null);
            renderQuestion();
        }

        function renderQuestion() {
            if (currentQuestionIndex >= questions.length) return;

            const question = questions[currentQuestionIndex];
            questionText.innerHTML = `
                <h3>Question ${currentQuestionIndex + 1} of ${questions.length}</h3>
                <p>${question.question_text}</p>
            `;

            optionsContainer.innerHTML = [1, 2, 3, 4, 5].map(rating => `
                <button type="button" 
                    class="option-button ${responses[question.question_id] === rating ? 'selected' : ''}"
                    onclick="selectRating(${question.question_id}, ${rating})">
                    ${rating}
                </button>
            `).join('');

            updateNavigation();
            updateProgressBar();
        }

        function selectRating(questionId, rating) {
            responses[questionId] = rating;
            renderQuestion();
        }

        function updateNavigation() {
            prevBtn.style.display = currentQuestionIndex > 0 ? 'block' : 'none';
            nextBtn.style.display = currentQuestionIndex < questions.length - 1 ? 'block' : 'none';
            submitBtn.style.display = currentQuestionIndex === questions.length - 1 ? 'block' : 'none';
            commentSection.style.display = currentQuestionIndex === questions.length - 1 ? 'block' : 'none';
        }

        function updateProgressBar() {
            const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
            progressBar.style.width = `${progress}%`;
        }

        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                renderQuestion();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < questions.length - 1 && responses[questions[currentQuestionIndex].question_id] !== null) {
                currentQuestionIndex++;
                renderQuestion();
            } else {
                alert('Please select a rating before proceeding.');
            }
        });

        function validateForm() {
            const unansweredQuestions = questions.filter(q => responses[q.question_id] === null);
            if (unansweredQuestions.length > 0) {
                alert('Please answer all questions before submitting.');
                return false;
            }

            const form = document.getElementById('evaluationForm');
            Object.entries(responses).forEach(([questionId, rating]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `responses[${questionId}]`;
                input.value = rating;
                form.appendChild(input);
            });

            return true;
        }

        // Initialize on page load
        window.addEventListener('load', initializeResponses);
    </script>
</body>
</html>