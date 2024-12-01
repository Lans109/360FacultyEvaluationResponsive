<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Generate a CSRF token if one doesn't exist in the session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

// Display status messages if set
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Include status handling layout for displaying the message
    include '../../../frontend/layout/status_handling.php';

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

// Handle search and role filter inputs, store them in session for persistence across pages
if (isset($_GET['search'])) {
    $_SESSION['search'] = mysqli_real_escape_string($con, $_GET['search']);
}
if (isset($_GET['role_filter'])) {
    $_SESSION['role_filter'] = mysqli_real_escape_string($con, $_GET['role_filter']);
}

// Use session values if set, otherwise default to empty
$search = $_SESSION['search'] ?? '';
$role_filter = $_SESSION['role_filter'] ?? '';

$query_accounts = "
    SELECT chair_id AS account_id, CONCAT(first_name, ' ', last_name) AS name, username, email, 'Program Chair' AS role 
    FROM program_chairs 
    WHERE 1=1
    UNION
    SELECT student_id AS account_id, CONCAT(first_name, ' ', last_name) AS name, username, email, 'Student' AS role 
    FROM students 
    WHERE 1=1
    UNION
    SELECT faculty_id AS account_id, CONCAT(first_name, ' ', last_name) AS name, username, email, 'Faculty' AS role 
    FROM faculty 
    WHERE 1=1
    UNION
    SELECT admin_id AS account_id, CONCAT(first_name, ' ', last_name) AS name, username, email, 'Admin' AS role 
    FROM admins 
    WHERE 1=1";

// Initialize filters
$filters = "";

// Search filter
if (!empty($search)) {
    $search_filter = " AND (CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";
    $query_accounts = str_replace("WHERE 1=1", "WHERE 1=1 $search_filter", $query_accounts);
}

// Role filter
if (!empty($role_filter)) {
    $role_filter_clause = " AND role = '$role_filter'";
    $filters .= $role_filter_clause;
}

// Add role filter after UNION
if (!empty($filters)) {
    $query_accounts = "SELECT * FROM ($query_accounts) AS combined_accounts WHERE 1=1 $filters";
}

$result_accounts = mysqli_query($con, $query_accounts);

$num_rows = mysqli_num_rows($result_accounts);

// Reset filters if the reset request is made
if (isset($_GET['reset_filters'])) {
    unset($_SESSION['search']);
    unset($_SESSION['role_filter']);
    header("Location: accounts.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Accounts</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
    <?php include '../../../frontend/layout/confirmation_modal.php'; ?>

</head>

<body>
    <div id="loader" class="loader"></div>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <div>
                <h1>Accounts Management</h1>
            </div>
        </div>
        <div class="content">
            <div class="upperContent">
                <div>
                    <p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Account' : 'Accounts' ?></p>
                </div>
                <!-- Search and Filter Form -->
                <div class="search-filter">
                    <form method="GET" action="">
                        <div class="form-group">
                            <div class="search-container">
                                <input type="text" placeholder="Search..." id="search" name="search"
                                    class="search-input">
                                <button type="submit" class="search-button">
                                    <i class="fa fa-search"></i> <!-- Magnifying Glass Icon -->
                                </button>
                            </div>
                            <div class="select-container">
                                <div class="select-wrapper">
                                    <select id="role_filter" name="role_filter" class="custom-select">
                                        <option value="" <?= $role_filter == '' ? 'selected' : '' ?>>All Roles</option>
                                        <option value="Student" <?= $role_filter == 'Student' ? 'selected' : '' ?>>Student
                                        </option>
                                        <option value="Faculty" <?= $role_filter == 'Faculty' ? 'selected' : '' ?>>Faculty
                                        </option>
                                        <option value="Program Chair" <?= $role_filter == 'Program Chair' ? 'selected' : '' ?>>Program Chair</option>
                                        <option value="Admin" <?= $role_filter == 'Admin' ? 'selected' : '' ?>>Admin
                                        </option>
                                    </select>
                                    <i class="fa fa-chevron-down select-icon"></i> <!-- Icon for dropdown -->
                                </div>
                            </div>
                            <button type="submit" class="fitler-btn"><i class="fa fa-filter" aria-hidden="true"></i>
                                Filter</button>
                            <a href="accounts.php?reset_filters=1" class="fitler-btn">
                                <i class="fa fa-eraser"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th width="200px">ID</th>
                            <th width="300px">Name</th>
                            <th width="200px">Username</th>
                            <th width="300px">Email</th>
                            <th width="200px">Role</th>
                            <th width="50px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($account = mysqli_fetch_assoc($result_accounts)): ?>
                            <tr>
                                <td><?php echo $account['account_id']; ?></td>
                                <td><?php echo $account['name']; ?></td>
                                <td><?php echo $account['username']; ?></td>
                                <td><?php echo $account['email']; ?></td>
                                <td><?php echo $account['role']; ?></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="edit-btn" data-toggle="modal"
                                            data-target="#editModal<?php echo $account['account_id']; ?>"
                                            data-id="<?php echo $account['account_id']; ?>"
                                            data-name="<?php echo $account['name']; ?>"
                                            data-username="<?php echo $account['username']; ?>"
                                            data-email="<?php echo $account['email']; ?>"
                                            data-role="<?php echo $account['role']; ?>">
                                            <img src="../../../frontend/assets/icons/edit.svg">
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Account Modal -->
                            <div class="modal fade" id="editModal<?php echo $account['account_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Account</h5>
                                            <span class="close" class="close" data-dismiss="modal" aria-label="Close">
                                                <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                                            </span>
                                        </div>
                                        <form id="editForm<?php echo $account['account_id']; ?>" method="POST"
                                            action="update_account.php">
                                            <input type="hidden" name="csrf_token"
                                                value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" id="role" name="role"
                                                value="<?php echo $account['role']; ?>">
                                            <div class="modal-body">
                                                <input type="hidden" name="account_id"
                                                    value="<?php echo $account['account_id']; ?>">
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" name="username" class="form-control"
                                                        value="<?php echo $account['username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="<?php echo $account['email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Enter new password (leave blank to keep current)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn" id="openConfirmationModalBtn">Save
                                                    changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>


                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>