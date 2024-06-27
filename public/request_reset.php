<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    try {
        // Check if the email exists
        $stmt = $pdo->prepare('SELECT * FROM User WHERE Email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour
            
            // Insert token into the database
            $stmt = $pdo->prepare('INSERT INTO PasswordReset (UserID, Token, Expiry) VALUES (?, ?, ?)');
            $stmt->execute([$user['UserID'], $token, $expiry]);
            
            // Send email to user with the reset link
            $resetLink = "http://localhost/rs/public/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password: \n\n$resetLink";
            mail($email, $subject, $message, 'From: no-reply@yourdomain.com');
            
            $_SESSION['success_message'] = "Password reset link has been sent to your email.";
            header("Location: homepage.php");
            exit();
        } else {
            $errorMessage = "No account found with that email address.";
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
    <title>Request Password Reset</title>
    <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
</head>
<body>
    <?php include('../templates/header.php'); ?>
    <div class="signup-container">
        <form class="signup-form" action="request_reset.php" method="post">
            <h2>Request Password Reset</h2>
            <div class="error-message"><?php if (isset($errorMessage)) echo htmlspecialchars($errorMessage); ?></div>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php include('../templates/footer.php'); ?>
</body>
</html>
