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

// Get the current date
$current_date = date('Y-m-d');

// Fetch the current evaluation period (active and ongoing)
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
        status = 'active' 
        AND start_date <= '$current_date' 
        AND end_date >= '$current_date' 
    ORDER BY start_date DESC 
    LIMIT 1"; // Only fetch the active evaluation period that is ongoing

$result = mysqli_query($con, $query);

// Check if there is an active evaluation period
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Fetch the current evaluation
$current_evaluation = mysqli_fetch_assoc($result);

// Check if the current evaluation is available
if (!$current_evaluation) {
    // Handle case where no active evaluation period is found
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'No active evaluation period found.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
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
                                <div class="scorings">
                                    <h3>Weighted Scoring System</h3>
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
                                <div class="schedule">
                                <form name="updateEvaluationForm" action="update_evaluation.php" method="post">
                                    <div class="mt-4">
                                        <h3>Evaluation Schedule</h3>
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
                                <div class="status">
                                    <h3>Evaluation Status</h3>
                                    <div class="evaluation-details">
                                        <div class="form-group">
                                            <select id="status" name="evaluation_status" required>
                                                <option value="closed" <?php echo ($evaluation_status === 'closed') ? 'selected' : ''; ?>>Closed</option>
                                                <option value="active" <?php echo ($evaluation_status === 'active') ? 'selected' : ''; ?>>
                                                    Active</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="update-evaluation">
                                    <div class="form-group">
                                        <button class="add-btn" type="submit">
                                            <img src="../../../frontend/assets/icons/update.svg">&nbsp;Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="disseminate">
                                <form action="disseminate_surveys.php" name="disseminateEvaluationForm" method="POST">
                                    <input type="hidden" name="period_id" value="<?php echo $current_evaluation['period_id']; ?>">
                                    <input type="hidden" name="survey_id" value="1">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <button class="add-btn" type="submit" <?php echo $button_disabled; ?>>
                                        <img src="../../../frontend/assets/icons/spread.svg">&nbsp;Disseminate
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        No active evaluation period found.
                    </div>
                <?php endif; ?>
            </div>

        </main>
        <!-- for sidebar button -->
        <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>