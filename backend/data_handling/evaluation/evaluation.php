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

// Fetch the current evaluation period
$query = "SELECT period_id, semester, academic_year, status, start_date, end_date FROM evaluation_periods"; // Get the latest active evaluation period

$result = mysqli_query($con, $query);

// Check for results
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Fetch the current evaluation
$current_evaluation = mysqli_fetch_assoc($result);
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
                                <p class="card-title">Semester:
                                    <?php echo htmlspecialchars($current_evaluation['semester']); ?>
                                </p>
                                <p class="card-title">Status:
                                    <?php
                                    $status = htmlspecialchars($current_evaluation['status']);
                                    switch ($status) {
                                        case 'completed':
                                            echo '<span class="text-success">Completed</span>';
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
                                </p><br>
                            </div>

                            <div class="evaluation-status">
                                <div class="mt-4">
                                    <h3>Update Evaluation</h3>
                                    <form action="update_evaluation.php" method="post">
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <input type="hidden" name="period_id"
                                            value="<?php echo htmlspecialchars($current_evaluation['period_id']); ?>">

                                        <div class="form-group">
                                            <label for="start_date">
                                                <p>Start Date:</p>
                                            </label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="<?php echo htmlspecialchars($current_evaluation['start_date']); ?>"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="end_date">
                                                <p>End Date:</p>
                                            </label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="<?php echo htmlspecialchars($current_evaluation['end_date']); ?>"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">
                                                <p>Status:</p>
                                            </label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="completed" <?php echo ($status === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="active" <?php echo ($status === 'active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="upcoming" <?php echo ($status === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                                            </select>
                                        </div>

                                        <button class="add-btn" type="submit">
                                            <img src="../../../frontend/assets/icons/update.svg">&nbsp;Update
                                            Evaluation&nbsp;
                                        </button>
                                    </form>
                                </div>
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
        <script type="text/javascript" src="../../../frontend/layout/app.js" defer></scr >
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>