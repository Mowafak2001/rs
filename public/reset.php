<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        try {
            // Verify the token
            $stmt = $pdo->prepare('SELECT * FROM PasswordReset WHERE Token = ? AND Expiry > NOW()');
            $stmt->execute([$token]);
            $reset = $stmt->fetch();
            
            if ($reset) {
                // Update the user's password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE User SET Password = ? WHERE UserID = ?');
                $stmt->execute([$hashedPassword, $reset['UserID']]);
                
                // Remove the token
                $stmt = $pdo->prepare('DELETE FROM PasswordReset WHERE Token = ?');
                $stmt->execute([$token]);
                
                $_SESSION['success_message'] = "Password reset successful!";
                header("Location: signin.php");
                exit();
            } else {
                $errorMessage = "Invalid or expired token.";
            }
        } catch (Exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
</head>
<body>
    <?php include('../templates/header.php'); ?>
    <div class="signup-container">
        <form class="signup-form" action="reset_password.php" method="post">
            <h2>Reset Password</h2>
            <div class="error-message"><?php if (isset($errorMessage)) echo htmlspecialchars($errorMessage); ?></div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
    <?php include('../templates/footer.php'); ?>
</body>
</html>
