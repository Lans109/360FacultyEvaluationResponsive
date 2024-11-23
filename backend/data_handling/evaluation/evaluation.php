<?php
// Connect to your database
include '../../db/dbconnect.php';

// Get the current date
$current_date = date('Y-m-d');

// Fetch the current evaluation period
$query = "SELECT period_id, semester, academic_year, status FROM evaluation_periods
          WHERE start_date <= '$current_date' AND end_date >= '$current_date'
          ORDER BY period_id DESC LIMIT 1"; // Get the latest active evaluation period
$result = mysqli_query($con, $query);

// Check for results
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Fetch the current evaluation
$current_evaluation = mysqli_fetch_assoc($result);

// Handle form submission for updating the status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    $period_id = $current_evaluation['period_id'];

    // Update the status in the database
    $update_query = "UPDATE evaluation_periods SET status='$new_status' WHERE period_id='$period_id'";
    if (mysqli_query($con, $update_query)) {
        // Refresh the evaluation status
        header("Location: evaluation.php");
        exit;
    } else {
        echo '<div class="alert alert-danger">Error updating status: ' . mysqli_error($con) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <title>Current Evaluation Status</title>
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to change the evaluation status?");
        }
    </script>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <main>
            <div class="upperMain">
                <div><h1>Current Evaluation Status</h1></div>
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
                                        <?php echo htmlspecialchars($current_evaluation['semester']); ?></p>
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
                                    <!-- Update Status Form -->
                                    <div class="mt-4">
                                        <h3>Update Evaluation Status</h3>
                                        <form action="update_evaluation.php" method="post" onsubmit="return confirmUpdate();">
                                            <input type="hidden" name="period_id"
                                                value="<?php echo htmlspecialchars($current_evaluation['period_id']); ?>">
                                            <div class="form-group">
                                                <label for="status"><p>Status:</p></label>
                                                <select class="form-control" id="status" name="status" required>
                                                    <option value="completed" <?php echo ($status === 'completed') ? 'selected' : ''; ?>>
                                                        Completed</option>
                                                    <option value="active" <?php echo ($status === 'active') ? 'selected' : ''; ?>>
                                                        Active</option>
                                                    <option value="upcoming" <?php echo ($status === 'upcoming') ? 'selected' : ''; ?>>
                                                        Upcoming</option>
                                                </select>
                                            </div>
                                            <button class="add-btn" button type="submit" name="update_status">
                                                <img src="../../../frontend/assets/icons/update.svg">&nbsp;Update Status&nbsp;
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
        <script type="text/javascript" src="../../../frontend/layout/app.js" defer></scr>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>