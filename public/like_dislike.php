<?php
require('../config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id'];
    $recipeID = $_POST['recipe_id'];
    $action = $_POST['action'];

    if ($action === 'like') {
        $stmt = $pdo->prepare("INSERT INTO Likes (UserID, RecipeID) VALUES (?, ?) ON DUPLICATE KEY UPDATE UserID = UserID");
        $stmt->execute([$userID, $recipeID]);
        $stmt = $pdo->prepare("DELETE FROM Dislikes WHERE UserID = ? AND RecipeID = ?");
        $stmt->execute([$userID, $recipeID]);
    } elseif ($action === 'dislike') {
        $stmt = $pdo->prepare("INSERT INTO Dislikes (UserID, RecipeID) VALUES (?, ?) ON DUPLICATE KEY UPDATE UserID = UserID");
        $stmt->execute([$userID, $recipeID]);
        $stmt = $pdo->prepare("DELETE FROM Likes WHERE UserID = ? AND RecipeID = ?");
        $stmt->execute([$userID, $recipeID]);
    }

    header('Location: R.php');
    exit;
}
?>
