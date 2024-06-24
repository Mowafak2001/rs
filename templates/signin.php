<?php
$title = "test";
include('../templates/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
</head>
<body>
    <div class="signup-container">
        <form class="signup-form" id="loginForm" action="/signin" method="post">
            <h2>Welcome Back!!!</h2>
            <h2>Login</h2>
            <!-- Display error message if it exists -->
            <div class="error-message"><!-- PHP/JS will go here --></div>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Log in</button>
        </form>
    </div>
</body>
</html>
