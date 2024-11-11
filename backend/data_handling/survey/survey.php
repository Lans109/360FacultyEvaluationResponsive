<?php
// Include database connection
include_once "../../../config.php";

include ROOT_PATH . '/backend/db/dbconnect.php';
include ROOT_PATH . '/frontend/layout/navbar.php';

// Initialize arrays for surveys, questions, and criteria
$surveys = [];
$criteria = [];

// Fetch all surveys for the dropdown
$surveys_query = "SELECT survey_id, survey_name FROM surveys";
$surveys_result = mysqli_query($con, $surveys_query);

if ($surveys_result) {
    while ($survey = mysqli_fetch_assoc($surveys_result)) {
        $surveys[$survey['survey_id']] = $survey['survey_name'];
    }
} else {
    echo "Error fetching surveys: " . mysqli_error($con);
}

// Fetch all criteria for the dropdown in Add Question modal
$criteria_query = "SELECT criteria_id, description FROM questions_criteria";
$criteria_result = mysqli_query($con, $criteria_query);

if ($criteria_result) {
    while ($criterion = mysqli_fetch_assoc($criteria_result)) {
        $criteria[$criterion['criteria_id']] = $criterion['description'];
    }
} else {
    echo "Error fetching criteria: " . mysqli_error($con);
}

// Fetch all questions grouped by survey
$questions_query = "
    SELECT q.question_id, q.question_code, q.question_text, q.survey_id, q.criteria_id, s.survey_name, c.description AS criteria_description
    FROM questions q
    LEFT JOIN surveys s ON q.survey_id = s.survey_id
    LEFT JOIN questions_criteria c ON q.criteria_id = c.criteria_id
    ORDER BY s.survey_id, q.question_id";
$questions_result = mysqli_query($con, $questions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Question Management</h2>

    <!-- Button to open the Add Question modal -->
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addQuestionModal">Add New Question</button>
    <button class="btn btn-info mb-3" data-toggle="modal" data-target="#addCriteriaModal">Add New Criteria</button>
    
<!-- Table displaying criteria -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Criteria Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($criteria as $criteria_id => $description): ?>
            <tr>
                <td><?php echo htmlspecialchars($description); ?></td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editCriteriaModal<?php echo $criteria_id; ?>">Edit</button>

                    <!-- Delete Button -->
                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteCriteriaModal<?php echo $criteria_id; ?>">Delete</button>
                    
                </td>
            </tr>

            <!-- Edit Criteria Modal -->
            <div class="modal fade" id="editCriteriaModal<?php echo $criteria_id; ?>" tabindex="-1" role="dialog" aria-labelledby="editCriteriaModalLabel<?php echo $criteria_id; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCriteriaModalLabel<?php echo $criteria_id; ?>">Edit Criteria</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="update_criteria.php" method="POST">
                            <input type="hidden" name="criteria_id" value="<?php echo $criteria_id; ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="description">Criteria Name</label>
                                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($description); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Criteria Modal -->
            <div class="modal fade" id="deleteCriteriaModal<?php echo $criteria_id; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCriteriaModalLabel<?php echo $criteria_id; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCriteriaModalLabel<?php echo $criteria_id; ?>">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="delete_criteria.php" method="POST">
                            <input type="hidden" name="criteria_id" value="<?php echo $criteria_id; ?>">
                            <div class="modal-body">
                                Are you sure you want to delete this criteria?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </tbody>
</table>



    <!-- Modal for adding question -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionModalLabel">Add New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_question.php" method="POST">
                    <!-- Survey Selection Dropdown -->
                    <div class="form-group">
                        <label for="survey">Select Survey</label>
                        <select class="form-control" id="survey" name="survey_id" required>
                            <option value="" disabled selected>Select Survey</option>
                            <?php foreach ($surveys as $survey_id => $survey_name): ?>
                                <option value="<?php echo $survey_id; ?>"><?php echo htmlspecialchars($survey_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Question Code Field -->
                    <div class="form-group">
                        <label for="questionCode">Question Code</label>
                        <input type="text" class="form-control" id="questionCode" name="question_code" required>
                    </div>

                    <!-- Question Text Field -->
                    <div class="form-group">
                        <label for="questionText">Question Text</label>
                        <input type="text" class="form-control" id="questionText" name="question_text" required>
                    </div>

                    <!-- Criteria Selection Dropdown -->
                    <div class="form-group">
                        <label for="criteria">Select Criteria</label>
                        <select class="form-control" id="criteria" name="criteria" required>
                            <option value="" disabled selected>Select criteria</option>
                            <?php foreach ($criteria as $criteria_id => $description): ?>
                                <option value="<?php echo $criteria_id; ?>"><?php echo htmlspecialchars($description); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Question</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Add Criteria Modal -->
    <div class="modal fade" id="addCriteriaModal" tabindex="-1" role="dialog" aria-labelledby="addCriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCriteriaModalLabel">Add New Criteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="add_criteria.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="description">Criteria Name</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_criteria" class="btn btn-primary">Add Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table of Surveys and Questions -->
    <?php
    $previous_survey_id = null;
    while ($question = mysqli_fetch_assoc($questions_result)):
        // Check if we need to start a new survey group
        if ($question['survey_id'] !== $previous_survey_id): 
            if ($previous_survey_id !== null): ?>
                </tbody>
            </table>
            <?php endif; ?>
            
            <h3><?php echo htmlspecialchars($question['survey_name']); ?></h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Question Code</th>
                        <th>Question Text</th>
                        <th>Criteria</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        <?php 
        endif;
        ?>

        <!-- Display the question details -->
        <tr>
            <td><?php echo htmlspecialchars($question['question_code']); ?></td>
            <td><?php echo htmlspecialchars($question['question_text']); ?></td>
            <td><?php echo htmlspecialchars($question['criteria_description']); ?></td>
            <td>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?php echo $question['question_id']; ?>"
                        data-id="<?php echo $question['question_id']; ?>"
                        data-question-code="<?php echo $question['question_code']; ?>"
                        data-question-text="<?php echo $question['question_text']; ?>">Edit</button>
                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $question['question_id']; ?>">Delete</button>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $question['question_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $question['question_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo $question['question_id']; ?>">Edit Question</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="update_question.php" method="POST">
                        <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="question_code">Question Code</label>
                                <input type="text" name="question_code" class="form-control" value="<?php echo htmlspecialchars($question['question_code']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="question_text">Question Text</label>
                                <input type="text" name="question_text" class="form-control" value="<?php echo htmlspecialchars($question['question_text']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="criteria">Select Criteria</label>
                                <select class="form-control" name="criteria" required>
                                    <option value="">Select Criteria</option>
                                    <?php foreach ($criteria as $criteria_id => $description): ?>
                                        <option value="<?php echo $criteria_id; ?>" <?php echo $criteria_id == $question['criteria_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($description); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal<?php echo $question['question_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $question['question_id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel<?php echo $question['question_id']; ?>">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="delete_question.php" method="POST">
                        <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
                        <div class="modal-body">
                            Are you sure you want to delete this question?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php 
        $previous_survey_id = $question['survey_id'];
        endwhile;
        ?>
    </tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
