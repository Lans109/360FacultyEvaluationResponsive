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
    header("Location: faculty/faculty_evaluation.php");
    exit();
}

$evaluation_id = $_GET['evaluation_id'];
$user_email = $_SESSION['email'];
$user_type = $_SESSION['user_type'];

// Fetch the evaluation and its questions
$sql = "SELECT q.question_id, q.question_text
FROM questions q
JOIN questions_criteria qc ON q.criteria_id = qc.criteria_id
WHERE qc.survey_id = (SELECT survey_id FROM evaluations WHERE evaluation_id = ?)";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evaluation_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

$sqlFaculty = "
SELECT 
    f.faculty_id, 
    CONCAT(f.first_name, ' ', f.last_name) AS faculty_name, 
    f.email, 
    f.profile_image, 
    c.course_name, 
    c.course_code, 
    cs.section
FROM 
    faculty f
JOIN 
    faculty_courses fc ON f.faculty_id = fc.faculty_id
JOIN 
    course_sections cs ON fc.course_section_id = cs.course_section_id
JOIN 
    courses c ON cs.course_id = c.course_id
JOIN 
    evaluations e ON cs.course_section_id = e.course_section_id
JOIN 
    evaluation_periods ep ON cs.period_id = ep.period_id
WHERE 
    e.evaluation_id = ?;";

$stmt = $conn->prepare($sqlFaculty);
$stmt->bind_param("i", $evaluation_id);
$stmt->execute();
$resultFaculty = $stmt->get_result();
$faculty_profile = $resultFaculty->fetch_assoc();


// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_responses'])) {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Temporary timestamp for presentation purposes
    $randomTimestamp = rand(strtotime("2024-11-19"), strtotime("2024-12-20"));
    $date = date("Y-m-d", $randomTimestamp);
    $time = date("H:i:s", $randomTimestamp);

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
        $stmt = $conn->prepare("UPDATE students_evaluations SET is_completed = 1, comments = ?, date_evaluated= ?, time_evaluated= ? WHERE evaluation_id = ? AND student_id = (SELECT student_id FROM students WHERE email = ?)");
        $stmt->bind_param("sssis", $comment, $date, $time, $evaluation_id, $user_email);
    } elseif ($user_type == 'faculty') {
        // For faculty: Update the faculty's evaluation status
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1 WHERE evaluation_id = ?");
        $stmt->bind_param("i", $evaluation_id);
    } elseif ($user_type == 'program_chair') {
        // For program chair: Update the program chair's evaluation status
        $stmt = $conn->prepare("UPDATE program_chair_evaluations SET is_completed = 1 WHERE evaluation_id = ? AND chair_id = (SELECT chair_id FROM program_chairs WHERE email = ?)");
        $stmt->bind_param("is", $evaluation_id, $user_email);
    }

    $stmt->execute();
    $stmt->close();

    // Redirect back to the appropriate evaluation page based on user type
    if ($user_type == 'students') {
        header("Location: students/student_evaluation.php");
    } elseif ($user_type == 'faculty') {
        header("Location: faculty/faculty_evaluation.php");
    } elseif ($user_type == 'program_chair') {
        header("Location: program_chair/program_chair_evaluation.php");
    }
    exit();
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
        <div class="nav-item">
            <a onclick="goBack()" class="return-button">
                <!-- <img src="../frontend/assets/icons/return.svg" class="button-icon"> -->
                Return
            </a>
        </div>
        <script>
            function goBack() {
                window.history.back(); // Navigates to the previous page
            }
        </script>

    </div>
    <div class="container">
        <div class="card">
            <div>
                <img width="150px" src="<?= $faculty_profile['profile_image']; ?>" alt="">
                <h2><?= $faculty_profile['faculty_name']; ?></h2>
                <p><?= $faculty_profile['course_code']; ?> - <?= $faculty_profile['course_name']; ?> - <?= $faculty_profile['section']; ?></p><br>
            </div>
            <div>
                
            </div>
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
                    <button type="submit" id="submitBtn" name="submit_responses" style="display:none;">Submit
                        Evaluation</button>
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
                <p style="padding: 15px;">${question.question_text}</p>
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