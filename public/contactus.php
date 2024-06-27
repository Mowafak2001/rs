<?php
$title = "Contact Us";
include('../templates/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
    <title>Contact Us</title>
</head>
<body>
    <div class="signup-container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        <?php endif; ?>
        <form class="signup-form" action="../public/contact.php" method="post">
            <h2>Contact Us</h2>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="reason" placeholder="Reason to Contact" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
