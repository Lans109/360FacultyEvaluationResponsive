<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

### Generate a CSRF token if one doesn't exist ###
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

### Display Status Messages ###
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

// Query to fetch the latest active evaluation period that is not completed
$period_query = "
    SELECT academic_year, semester, status, period_id, start_date, end_date
    FROM evaluation_periods 
    WHERE 
        is_completed = 0
    ORDER BY period_id ASC 
    LIMIT 1"; // Fetch the latest active evaluation for today that is not completed

$period_result = mysqli_query($con, $period_query);

// Check if any evaluation exists with is_completed = 0
if ($period_result && mysqli_num_rows($period_result) > 0) {
    $period_data = mysqli_fetch_assoc($period_result);

    // Store the period ID in the session
    $_SESSION['period_id'] = $period_data['period_id'];
} else {
    // If no evaluation is not completed, fetch the latest evaluation period
    $latest_query = "
        SELECT academic_year, semester, status, period_id, start_date, end_date
        FROM evaluation_periods 
        ORDER BY period_id DESC 
        LIMIT 1"; // Fetch the latest evaluation period

    $latest_result = mysqli_query($con, $latest_query);

    if ($latest_result && mysqli_num_rows($latest_result) > 0) {
        $latest_data = mysqli_fetch_assoc($latest_result);

        // Store the period ID in the session
        $_SESSION['period_id'] = $latest_data['period_id'];
    } else {
        // If no evaluation periods exist, set a fallback or clear session
        $_SESSION['period_id'] = 0; // Or any default value
    }
}

// Get the current date
$current_date = date('Y-m-d');
$current_year = date('Y'); // Get the current year
$years_to_show = 5; // Number of years to display, e.g., 5 years from current year

// Fetch the current evaluation period (active and ongoing)
if (isset($_SESSION['period_id']) && is_numeric($_SESSION['period_id'])) {
    $period_id = mysqli_real_escape_string($con, $_SESSION['period_id']); // Sanitize the input

    $query = "
        SELECT 
            period_id, 
            semester, 
            academic_year, 
            status, 
            start_date, 
            end_date,
            disseminated,
            student_scoring,
            self_scoring,
            peer_scoring,
            chair_scoring
        FROM 
            evaluation_periods
        WHERE 
            period_id = $period_id
        ORDER BY start_date DESC";
} else {
    // Handle missing or invalid period_id
    $query = null; // Or set a fallback query
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid or missing period ID.';
    header("Location: some_error_page.php");
    exit();
}

$result = mysqli_query($con, $query);

// Check if there is an active evaluation period
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Fetch the current evaluation
$current_evaluation = mysqli_fetch_assoc($result);

$is_closed_and_past = ($current_evaluation['status'] === 'closed' && $current_date > $current_evaluation['end_date']);

// Check if the current evaluation is available
if (!$current_evaluation) {
    // Handle case where no active evaluation period is found
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'No active evaluation period found.';
}

// Check if the evaluation has already been disseminated
$disseminated = $current_evaluation['disseminated'];

// Determine the button status based on dissemination status
$button_disabled = $disseminated == 1 ? 'disabled' : '';  // Disable button if disseminated
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php include '../../../frontend/layout/confirmation_modal.php'; ?>
	<?php include '../../../frontend/layout/navbar.php'; ?>
    <title>Current Evaluation Status</title>
</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <main>
            <div class="upperMain">
                <div>
                    <h1>Current Evaluation Status</h1>
                </div>
            </div>
            <div class="content">
                <h2>Evaluation Details</h2>
                <?php if ($current_evaluation): ?>
                    <div class="banner">
                        <div class="evaluation-status">
                                <div class="academic-year">
                                    <h3 class="card-title">Academic Year:
                                        <?php echo htmlspecialchars($current_evaluation['academic_year']); ?>
                                    </h3>
                                    <div class="evaluation-details">
                                        <p class="card-title">Semester:
                                            <?php echo htmlspecialchars($current_evaluation['semester']); ?>
                                        </p>
                                        <p class="card-title">Status:
                                            <?php
                                            $evaluation_status = htmlspecialchars($current_evaluation['status']);
                                            switch ($evaluation_status) {
                                                case 'closed':
                                                    echo '<span class="text-success">Closed</span>';
                                                    break;
                                                case 'active':
                                                    echo '<span class="text-info">Active</span>';
                                                    break;
                                                case 'upcoming':
                                                    echo '<span class="text-warning">Upcoming</span>';
                                                    break;
                                                default:
                                                    echo '<span class="text-muted">Unknown Status</span>';
                                                    break;
                                            }
                                            ?>
                                    </div>
                                    </p><br>
                                </div>
                                <div class="disseminate">
                                    <p>Dissemination</p>
                                    <form action="disseminate_surveys.php" name="disseminateEvaluationForm" method="POST">
                                        <div class="evaluation-details">  
                                        <input type="hidden" name="period_id" value="<?php echo $current_evaluation['period_id']; ?>">
                                            <input type="hidden" name="survey_id" value="1">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button class="view-btn" type="submit" <?php echo $button_disabled; ?>>
                                                Disseminate
                                            </button>
                                    </form>
                                    </div>
                                </div>
                                <div class="status">
                                    <p>Evaluation Status</p>
                                    <form name="updateEvaluationForm" action="update_evaluation.php" method="post">
                                    <div class="evaluation-details">
                                        <div class="form-group">
                                            <select id="status" name="evaluation_status" required>
                                                <option value="closed" <?php echo ($evaluation_status === 'closed') ? 'selected' : ''; ?>>Closed</option>
                                                <option value="active" <?php echo ($evaluation_status === 'active') ? 'selected' : ''; ?>>Active</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="schedule">
                                    <div class="mt-4">
                                        <p>Evaluation Schedule</p>
                                        <div class="evaluation-details">
                                            <input type="hidden" name="csrf_token"
                                                value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="period_id"
                                                value="<?php echo htmlspecialchars($current_evaluation['period_id']); ?>">
                                            <div class="form-group">
                                                <label for="start_date">
                                                    <p>Start Date:</p>
                                                    <input type="date" class="form-control" id="start_date"
                                                        name="start_date"
                                                        value="<?php echo htmlspecialchars($current_evaluation['start_date']); ?>"
                                                        required>
                                            </div>
                                            <div class="form-group">
                                                <p>End Date:</p>
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                    value="<?php echo htmlspecialchars($current_evaluation['end_date']); ?>"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="scorings">
                                    <p>Weighted Scoring</p>
                                    <div class="evaluation-details">
                                        <div class="form-group">
                                            <div class="score">
                                                <p>Student Scoring:</p>
                                                <input type="text" name="student_scoring" id="student_scoring"
                                                    value="<?= $current_evaluation['student_scoring'] ?>"><span>&nbsp;%</span>
                                            </div>
                                            <input type="range" class="form-control" id="student_slider" min="0" max="100"
                                                value="<?= $current_evaluation['student_scoring'] ?>" step="1">
                                        </div>
                                        <div class="form-group">
                                            <div class="score">
                                                <p>Chair/Dean Scoring:</p>
                                                <input type="text" name="chair_scoring" id="chair_scoring"
                                                    value="<?= $current_evaluation['chair_scoring'] ?>"><span>&nbsp;%</span>
                                            </div>
                                            <input type="range" class="form-control" id="chair_slider" min="0" max="100"
                                                value="<?= $current_evaluation['chair_scoring'] ?>" step="1">
                                        </div>
                                        <div class="form-group">
                                            <div class="score">
                                                <p>Peer Scoring:</p>
                                                <input type="text" name="peer_scoring" id="peer_scoring"
                                                    value="<?= $current_evaluation['peer_scoring'] ?>"><span>&nbsp;%</span>
                                            </div>
                                            <input type="range" class="form-control" id="peer_slider" min="0" max="100"
                                                value="<?= $current_evaluation['peer_scoring'] ?>" step="1">
                                        </div>
                                        <div class="form-group">
                                            <div class="score">
                                                <p>Self Scoring:</p>
                                                <input type="text" name="self_scoring" id="self_scoring"
                                                    value="<?= $current_evaluation['self_scoring'] ?>"><span>&nbsp;%</span>
                                            </div>
                                            <input type="range" class="form-control" id="self_slider" min="0" max="100"
                                                value="<?= $current_evaluation['self_scoring'] ?>" step="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="update-evaluation">
                                <p>Action</p>
                                    <div class="evaluation-details">
                                        <div class="form-group">
                                            <button class="add-btn" type="submit">
                                                <img src="../../../frontend/assets/icons/update.svg">&nbsp;Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if ($is_closed_and_past): ?>
                    <div class="banner">
                        <button id="openModalBtn-add-evaluation" class="add-btn" data-toggle="modal" data-target="#addEvaluationModal">
                            <img src="../../../frontend/assets/icons/add.svg">&nbsp;Start New Evaluation&nbsp;
                        </button>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="banner">
                    <p>No active evaluation period found.</p>
                </div>
            <?php endif; ?>
            </div>
        </main>

        <!-- Add Evaluation Modal -->
        <div class="modal" id="addEvaluationModal" tabindex="-1" role="dialog" aria-labelledby="addEvaluationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEvaluationModalLabel">Start New Evaluation</h5>
                        <span class="close" data-dismiss="modal" aria-label="Close">
                            <img src="../../../frontend/assets/icons/close2.svg" alt="Close">
                        </span>
                    </div>
                    <!-- Add Evaluation Form -->
                    <form id="startNewEvaluationForm" name="startNewEvaluationForm" action="start_new_evaluation.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select name="semester" class="form-control" required>
                                    <option value="">Select Semester</option>
                                    <option value="1st">1st Semester</option>
                                    <option value="2nd">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="academic_year">Academic Year</label>
                                <select name="academic_year" class="form-control" required>
                                    <?php for ($i = 0; $i < $years_to_show; $i++): ?>
                                        <?php
                                            $start_year = $current_year + $i; 
                                            $end_year = $start_year + 1;
                                        ?>
                                        <option value="<?php echo $start_year . '-' . $end_year; ?>">
                                            <?php echo $start_year . '-' . $end_year; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                            <button type="submit" class="save-btn">Add Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- for sidebar button -->
        <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>