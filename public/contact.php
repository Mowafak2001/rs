<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $reason = $_POST['reason'];

    // Here you can add the code to save the contact information to the database or send an email.

    $_SESSION['success_message'] = 'Thank you for contacting us. We will get back to you soon!';
    header('Location: contactus.php');
    exit();
} else {
    header('Location: contactus.php');
    exit();
}
?>
