<?php
// Include the database connection file
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

// Display Status Messages if any
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

// Get the survey_id from the URL
$survey_id = isset($_GET['survey_id']) ? mysqli_real_escape_string($con, $_GET['survey_id']) : '';

// Fetch survey details
$survey_query = "
    SELECT survey_name
    FROM surveys 
    WHERE survey_id = '$survey_id'
";
$survey_result = mysqli_query($con, $survey_query);
$survey = mysqli_fetch_array($survey_result);

// Fetch questions grouped by criteria
$questions_query = "
SELECT 
    q.question_id,
    q.question_text,
    q.question_code,
    c.criteria_id,
    c.description
FROM 
    questions_criteria c
LEFT JOIN 
    questions q ON q.criteria_id = c.criteria_id
WHERE 
    c.survey_id = '$survey_id'
ORDER BY 
    c.description;
";
$questions_result = mysqli_query($con, $questions_query);

$num_rows = mysqli_num_rows($questions_result);
// Group questions by criteria
$questions_by_criteria = [];
while ($question = mysqli_fetch_assoc($questions_result)) {
    $questions_by_criteria[$question['description']][] = $question;
}

$num_criteria = count($questions_by_criteria);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Questions</title>
    <link rel="stylesheet" href="../../../frontend/templates/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>
</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/sidebar.php'; ?>


    <main>
        <div class="upperMain">
            <div>
                <h1><?= htmlspecialchars($survey['survey_name']); ?></h1>
            </div>
        </div>
        <div class="content">
            <div class="legend">
                <div>
                    <p>Showing <?= $num_rows ?>
                        <?= $num_rows == 1 ? 'Question,' : 'Questions, ' ?><?= $num_criteria ?><?= $num_criteria == 1 ? ' Criteria,' : ' Criterias' ?>
                    </p>
                </div>
                <div class="rating-legend">
                    <div>
                        <p>5: Outstanding</p>
                    </div>
                    <div>
                        <p>4: Exceeds Standard</p>
                    </div>
                    <div>
                        <p>3: Meets Standard</p>
                    </div>
                    <div>
                        <p>2: Partially Meets Standard</p>
                    </div>
                    <div>
                        <p>1: Does not Meet Standard</p>
                    </div>
                </div>
                <div>
                    <button id="openModalBtn-add-criteria" class="add-btn" data-toggle="modal"
                        data-target="#addCriteriaModal">
                        <img src="../../../frontend/assets/icons/add.svg">&nbsp;Criteria&nbsp;
                    </button>
                </div>
            </div>
            <?php foreach ($questions_by_criteria as $criteria_description => $questions): ?>
                <div class="survey-box">
                    <div class="criteria-header">
                        <div class="criteria-title"><?= htmlspecialchars($criteria_description); ?></div>

                        <div>
                            <form name="deleteForm" action="delete_criteria.php" method="POST">
                                <!-- Hidden input to pass the course_id -->
                                <input type="hidden" name="criteria_id" value="<?= $questions[0]['criteria_id']; ?>">
                                <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <!-- Submit button for deleting the course -->
                                <button type="submit" class="cancel-btn">
                                    <img src="../../../frontend/assets/icons/close.svg" alt="Delete Icon">
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th width="100px">Q. Code</th>
                                    <th>Description</th>
                                    <th width="500px"></th>
                                    <th width="100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions as $question): ?>
                                    <?php if (empty($question['question_code']) || empty($question['question_text'])): ?>
                                        <tr>
                                            <td colspan="4">No question available on this criteria.</td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td><?= htmlspecialchars($question['question_code']); ?></td>
                                            <td><?= htmlspecialchars($question['question_text']); ?></td>
                                            <td>
                                                <div class="visual-rating">
                                                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="edit-btn" data-toggle="modal"
                                                        data-target="#editModal<?= $question['question_id']; ?>"
                                                        data-question_id="<?= $question['question_id']; ?>"
                                                        data-question_text="<?= htmlspecialchars($question['question_text']); ?>"
                                                        data-criteria_id="<?= $question['criteria_id']; ?>"
                                                        data-criteria_description="<?= htmlspecialchars($criteria_description); ?>">
                                                        <img src="../../../frontend/assets/icons/edit.svg">
                                                    </button>
                                                    <form name="deleteForm" action="delete_question.php" method="POST">
                                                        <!-- Hidden input to pass the course_id -->
                                                        <input type="hidden" name="question_id"
                                                            value="<?= $question['question_id']; ?>">
                                                        <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">
                                                        <input type="hidden" name="csrf_token"
                                                            value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        <!-- Submit button for deleting the course -->
                                                        <button type="submit" class="delete-btn">
                                                            <img src="../../../frontend/assets/icons/delete.svg" alt="Delete Icon">
                                                        </button>
                                                    </form>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <!-- Modal for editing question -->
                                    <div class="modal fade" id="editModal<?= $question['question_id']; ?>" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Question</h5>
                                                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                                                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                                                    </span>
                                                </div>
                                                <form id="editForm<?php echo $question['question_id']; ?>"
                                                    action="update_question.php" method="POST">
                                                    <input type="hidden" name="csrf_token"
                                                        value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">
                                                        <input type="hidden" name="question_id"
                                                            value="<?= $question['question_id']; ?>">
                                                        <input type="hidden" name="criteria_id"
                                                            value="<?= $question['criteria_id']; ?>"> <!-- Add this line -->
                                                        <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">
                                                        <!-- Add this line -->

                                                        <!-- Edit Question Code -->
                                                        <div class="form-group">
                                                            <label>Question Code</label>
                                                            <input type="text" class="form-control" name="question_code"
                                                                value="<?= htmlspecialchars($question['question_code']); ?>"
                                                                required>
                                                        </div>

                                                        <!-- Edit Question Text -->
                                                        <div class="form-group">
                                                            <label>Question Text</label>
                                                            <textarea class="form-control" name="question_text" rows="3"
                                                                required><?= htmlspecialchars($question['question_text']); ?></textarea>
                                                        </div>


                                                    </div>


                                                    <div class="modal-footer">
                                                        <button type="button" class="cancel-btn"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="save-btn"
                                                            id="openConfirmationModalBtn">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>

                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="4" class="add-question">
                                        <div>
                                            <button class="insert-btn full-td-btn" data-toggle="modal"
                                                data-target="#addQuestionModal"
                                                data-criteria_id="<?= $questions[0]['criteria_id']; ?>"
                                                data-criteria_description="<?= htmlspecialchars($criteria_description); ?>"
                                                onclick="setCriteriaData(this)">
                                                <img src="../../../frontend/assets/icons/add.svg">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

    <!-- Modal for adding new criteria -->
    <div class="modal fade" id="addCriteriaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Criteria</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>
                </div>
                <div class="modal-body">
                    <form action="add_criteria.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">

                        <!-- New Criteria Description -->
                        <div class="form-group">
                            <label>Criteria Description</label>
                            <input type="text" class="form-control" name="criteria_description" required>
                        </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="save-btn">Save Criteria</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for adding new question -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>
                </div>
                <div class="modal-body">
                    <form action="add_question.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="survey_id" value="<?= $survey_id; ?>">
                        <input type="hidden" name="criteria_id" id="criteria_id" value="">
                        <input type="hidden" name="criteria_description" id="criteria_description" value="">

                        <!-- New Question Code -->
                        <div class="form-group">
                            <label>Question Code</label>
                            <input type="text" class="form-control" name="question_code" required>
                        </div>

                        <!-- New Question Text -->
                        <div class="form-group">
                            <label>Question Text</label>
                            <textarea class="form-control" name="question_text" rows="3" required></textarea>
                        </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="save-btn">Save Question</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript to dynamically set criteria data in the modal
        function setCriteriaData(button) {
            // Get criteria_id and criteria_description from the button's data attributes
            var criteriaId = $(button).data('criteria_id');
            var criteriaDescription = $(button).data('criteria_description');

            // Set these values in the hidden inputs in the modal
            $('#criteria_id').val(criteriaId);
            $('#criteria_description').val(criteriaDescription);
        }
    </script>
</body>

</html>