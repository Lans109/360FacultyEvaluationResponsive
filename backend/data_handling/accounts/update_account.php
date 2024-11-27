<?php
// Include the database connection
include_once "../../db/dbconnect.php";

// Start the session for CSRF token validation and status messages
session_start();

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token is missing or invalid
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token.';
        header("Location: accounts.php");
        exit;
    }

    // Retrieve form data with escaping to prevent SQL injection
    $account_id = mysqli_real_escape_string($con, $_POST['account_id']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : null;

    // Validate required fields
    if (empty($account_id) || empty($username) || empty($email) || empty($role)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: All fields except password are required.';
        header("Location: accounts.php");
        exit;
    }

    // Map the role to the corresponding database table
    switch ($role) {
        case 'Student':
            $table = 'students';
            $id_column = 'student_id';
            break;
        case 'Faculty':
            $table = 'faculty';
            $id_column = 'faculty_id';
            break;
        case 'Program Chair':
            $table = 'program_chairs';
            $id_column = 'chair_id';
            break;
        default:
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: Invalid role.';
            header("Location: accounts.php");
            exit;
    }

    // Build the update query
    $update_query = "UPDATE $table SET username = '$username', email = '$email'";

    // If password is provided, hash it and include in the update query
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $update_query .= ", password_hash = '$password_hash'";
    }

    $update_query .= " WHERE $id_column = '$account_id'";

    // Execute the update query
    if (mysqli_query($con, $update_query)) {
        // Unset CSRF token after the update
        unset($_SESSION['csrf_token']);

        // Optionally, regenerate the CSRF token if you plan to use it again
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Account updated successfully.';
        header("Location: accounts.php?status=success");
        exit;
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error updating account: ' . mysqli_error($con);
        header("Location: accounts.php");
        exit;
    }
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: accounts.php");
    exit;
}

?>
