<?php
// Include the database connection
include_once "../../db/dbconnect.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $account_id = mysqli_real_escape_string($con, $_POST['account_id']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : null;

    // Validate required fields
    if (empty($account_id) || empty($username) || empty($email) || empty($role)) {
        echo "Error: All fields except password are required.";
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
            echo "Error: Invalid role.";
            exit;
    }

    // Build the update query
    $update_query = "UPDATE $table SET username = '$username', email = '$email'";
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $update_query .= ", password_hash = '$password_hash'";
    }
    $update_query .= " WHERE $id_column = '$account_id'";

    // Execute the query
    if (mysqli_query($con, $update_query)) {
        echo "Account updated successfully.";
        // Redirect to the accounts page or display a success message
        header("Location: accounts.php?status=success");
        exit;
    } else {
        echo "Error updating account: " . mysqli_error($con);
    }
} else {
    echo "Invalid request.";
}
?>
