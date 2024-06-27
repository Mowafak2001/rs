<?php
$title = "Sign Up";
include('../templates/header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" type="text/css" href="../public/css/signup.css">
</head>

<body>
    <div class="signup-container"> <!-- Container for the signup form -->
        <form class="signup-form" action="process_signup.php" method="post"> <!-- Signup form -->
            <h2>Welcome foodie lovers!!!</h2>
            <h2>Join Us</h2>
            <input type="text" name="first_name" placeholder="First Name" required> <!-- Input field for first name -->
            <input type="text" name="last_name" placeholder="Last Name" required> <!-- Input field for last name -->
            <input type="email" name="email" placeholder="Email" required>  <!-- Input field for email -->
            <input type="password" name="password" placeholder="Password" required> <!-- Input field for password -->
            <input type="password" name="confirm_password" placeholder="Confirm Password" required> <!-- Input field for confirming password -->
            <button type="submit">Sign Up</button> <!-- Submit button -->
        </form>
    </div>
</body>

</html>

<?php include('../templates/footer.php'); ?>
