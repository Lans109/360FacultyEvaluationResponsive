<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel='stylesheet' href="./css/index-style.css">
    <title>Login</title>
    <nav class="topnav">

    </nav>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="logo">
                <img src="./assets/icons/LPU-LOGO.png" alt="Logo">
            </div>
            <div class="login-box">
                <form action="#" method="POST">
                    <div class="user-box">
                        <input type="text" id="email" name="email" required autocomplete="off">
                        <label for="email">Email</label>
                    </div>
                    <div class="user-box">
                        <input type="password" id="password" name="password" required autocomplete="off">
                        <label for="password">Password</label>
                    </div>
                    <button class="btn" type="submit" name="submit" value="Login">Login</button>

                    <div class="dropdown">
                        <label for="ID">Sign In As</label><br>
                        <select id="Role" name="Role">
                            <option value="Student">Student</option>
                            <option value="Faculty">Faculty</option>
                            <option value="ProgramChair">Program Chair</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>