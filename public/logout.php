<?php
session_start();

// Set logout success message
$_SESSION['logout_message'] = "Logout successful!";

// Destroy session
session_unset();
session_destroy();

// Redirect to the homepage
header("Location: homepage.php");
exit();
