<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM User WHERE Email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['user_name'] = $user['FirstName'];
            // Redirect to the homepage after successful login
            header("Location: homepage.php");
            exit();
        } else {
            $errorMessage = "Invalid email or password.";
        }
    } catch (Exception $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
</head>
<body>
    <?php include('../templates/header.php'); ?>
    <div class="signup-container">
        <form class="signup-form" id="loginForm" action="signin.php" method="post">
            <h2>Welcome Back!!!</h2>
            <h2>Login</h2>
            <!-- Display error message if it exists -->
            <div class="error-message"><?php if (isset($errorMessage)) echo htmlspecialchars($errorMessage); ?></div>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Log in</button>
            <p><a href="request_reset.php">Forgot Password?</a></p>
        </form>
    </div>
    <?php include('../templates/footer.php'); ?>
</body>
</html>
