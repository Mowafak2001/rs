<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare('INSERT INTO User (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
        
        // Set success message in session
        $_SESSION['success_message'] = "User registered successfully!";
        
        // Redirect to the homepage
        header("Location: homepage.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
