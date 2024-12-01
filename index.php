<?php 

include 'config.php';

session_start();

if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Output JavaScript to show an alert with the message
    echo "<script type='text/javascript'>alert('$message');</script>";

    // Clear session variables after displaying the message
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
    <link rel='stylesheet' href='frontend/templates/admin-style.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php include 'frontend/layout/confirmation_modal.php'; ?>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="backend/validation.php" method="POST">
        <div>
            <label for="username_or_email">Username or Email</label>
            <input type="text" id="username_or_email" name="username_or_email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
<script type="text/javascript" src="frontend/layout/app.js" defer></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
