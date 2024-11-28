<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Fetch survey data and count questions
$query = "
    SELECT 
        s.survey_id, 
        s.survey_name, 
        COUNT(q.question_id) AS total_questions
    FROM 
        surveys s
    LEFT JOIN 
        questions_criteria qc ON s.survey_id = qc.survey_id
    LEFT JOIN 
        questions q ON q.criteria_id = qc.criteria_id
    GROUP BY 
        s.survey_id
";
$result = mysqli_query($con, $query);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Management</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <div><h1>Survey Management</h1></div>
        </div>
        <div class="content">

            <?php while ($survey = mysqli_fetch_assoc($result)): ?>
                <div class="survey-banner">
                     <?php 
                        // Conditional statement to choose icon based on survey title
                        if (strpos($survey['survey_name'], 'Student') !== false) {
                            $icon = "student.svg";
                        } elseif (strpos($survey['survey_name'], 'Faculty') !== false) {
                            $icon = "faculty.svg";
                        } elseif (strpos($survey['survey_name'], 'Self') !== false) {
                            $icon = "faculty.svg";
                        } elseif (strpos($survey['survey_name'], 'Chair') !== false || strpos($survey['survey_name'], 'Dean') !== false) {
                            $icon = "department.svg";
                        } else {
                            $icon = "survey.svg"; // Default icon if no match
                        }
                    ?>
                    <img class="survey-icon" src="../../../frontend/assets/icons/<?php echo htmlspecialchars($icon); ?>">
                    <div class="survey-info">
                        <h3><?php echo htmlspecialchars($survey['survey_name']); ?></h3>
                        <p>Total Questions: <?php echo htmlspecialchars($survey['total_questions']); ?></p>
                        <div>
                            <!-- Edit Survey button with survey_id passed to the view_survey.php page -->
                            <button id="openModalBtn-add-course" class="view-btn" onclick="window.location.href='view_survey.php?survey_id=<?php echo $survey['survey_id']; ?>'">
                                <img src="../../../frontend/assets/icons/edit.svg">&nbsp;Edit Survey&nbsp;
                            </button>
                        </div>
                    </div>  
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
