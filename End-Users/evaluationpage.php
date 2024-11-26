<?php
session_start();
include("databasecon.php");

// Ensure the user is logged in and is either a student, faculty, or program chair
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['user_type'], ['students', 'faculty', 'program_chair'])) {
    header("Location: login_option.php");
    exit();
}

// Get the evaluation ID from the URL
if (!isset($_GET['evaluation_id'])) {
    header("Location: student_evaluation.php");
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
        $stmt = $conn->prepare("INSERT INTO responses (evaluation_id, question_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $evaluation_id, $question_id, $rating);
        $stmt->execute();
        $stmt->close();
    }

    // Update evaluation status and save the comment
    if ($user_type == 'students') {
        $stmt = $conn->prepare("UPDATE students_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND student_id = (SELECT student_id FROM students WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    } elseif ($user_type == 'faculty') {
        $stmt = $conn->prepare("UPDATE faculty_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND faculty_id = (SELECT faculty_id FROM faculty WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    } elseif ($user_type == 'program_chair') {
        $stmt = $conn->prepare("UPDATE program_chair_evaluations SET is_completed = 1, comments = ? WHERE evaluation_id = ? AND chair_id = (SELECT chair_id FROM program_chairs WHERE email = ?)");
        $stmt->bind_param("sis", $comment, $evaluation_id, $user_email);
    }

    $stmt->execute();
    $stmt->close();

    // Redirect based on user type
    if ($user_type == 'students') {
        header("Location: student_evaluation.php");
    } elseif ($user_type == 'faculty') {
        header("Location: faculty_evaluation.php");
    } elseif ($user_type == 'program_chair') {
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
    <title>Evaluation</title>
<style>
        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", Arial, sans-serif;
            background: linear-gradient(135deg, #7D0006, #D3D3D3);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            color: #000;
            line-height: 1.6;
            margin: 0;
        }
        
    /* Header Section */
        .header {
            background: #7D0006;
            padding: 1.5rem 0;
            text-align: center;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

    /* Header Navigation Links */
    .header nav a {
        color: var(--white);
        text-decoration: none;
        margin: 0 1rem;
        font-size: 1.1rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        background-color: #7D0006;
        border-radius: 8px;
        display: inline-block;
        transition: var(--transition-speed);
        box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .header nav a:hover {
        color: #000;
        text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
        box-shadow: 3px 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Container */
        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 1rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }

    /* Profile Card */
        .card {
            text-align: center;
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.25);
        }

    /* Button Styles */
        button {
            background: #7D0006;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #D3D3D3;
            color: #7D0006;
        }

        textarea {
            width: 100%;
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 1rem;
        }
</style>
    <script>
        let currentQuestionIndex = 0;
        let questions = <?php echo json_encode($questions); ?>;
        let responses = {};

        function initializeResponses() {
            questions.forEach(question => {
                responses[question.question_id] = null;
            });
        }

        function displayQuestion() {
            const questionContainer = document.getElementById('evaluationQuestions');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            questionContainer.innerHTML = '';

            if (currentQuestionIndex < questions.length) {
                const question = questions[currentQuestionIndex];
                questionContainer.innerHTML = `
                    <div>
                        <p>${question.question_text}</p>
                        ${[1, 2, 3, 4, 5].map(i => `
                            <label>
                                <input type="radio" name="responses[${question.question_id}]" value="${i}" onchange="storeResponse(${question.question_id}, ${i})">
                                ${i}
                            </label>
                        `).join('')}
                    </div>
                `;

                // Show or hide the "Previous" button based on the current question index
                if (currentQuestionIndex === 0) {
                    prevBtn.style.display = 'none';
                } else {
                    prevBtn.style.display = 'inline-block';
                }

                // Show or hide the "Next" button based on the current question index
                if (currentQuestionIndex === questions.length - 1) {
                    nextBtn.style.display = 'none';
                } else {
                    nextBtn.style.display = 'inline-block';
                }
            }
        }

        function storeResponse(questionId, rating) {
            responses[questionId] = rating;
        }

        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                displayQuestion();
            }
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                displayQuestion();
            }
        }

        window.onload = function() {
            initializeResponses();
            displayQuestion();
        };
    </script>
</head>

<body>
    <div class="header">
        <h1>Evaluation</h1>
        <nav>
            <a href="<?php echo $user_type == 'students' ? 'student_evaluation.php' : ($user_type == 'faculty' ? 'faculty_evaluation.php' : 'program_chair_evaluation.php'); ?>">Return</a>
        </nav>
    </div>
    <div class="container">
        <div class="card">
            <h2>Evaluation Questions</h2>
            <form method="POST" onsubmit="return validateForm()">
                <div id="evaluationQuestions"></div>
                <div id="commentSection" style="display:none;">
                    <label for="comment">Additional Comment:</label>
                    <textarea id="comment" name="comment"></textarea>
                </div>
                <button type="button" id="prevBtn" onclick="prevQuestion()">Previous</button>
                <button type="button" id="nextBtn" onclick="nextQuestion()">Next</button>
                <button type="submit" id="submitBtn" name="submit_responses">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>