<?php
session_start();
include('../config.php');

if (!isset($_GET['recipe_id'])) {
    $_SESSION['error_message'] = "No recipe ID provided.";
    header("Location: R.php");
    exit;
}

$recipeID = $_GET['recipe_id'];

try {
    // Start a transaction
    $pdo->beginTransaction();

    // Delete the recipe
    $stmt = $pdo->prepare('DELETE FROM Recipe WHERE RecipeID = ?');
    $stmt->execute([$recipeID]);

    // Commit the transaction
    $pdo->commit();

    $_SESSION['success_message'] = "Recipe removed successfully.";
} catch (Exception $e) {
    // Roll back the transaction if something failed
    $pdo->rollBack();
    $_SESSION['error_message'] = "Error removing recipe: " . $e->getMessage();
}

header("Location: R.php");
exit;
?>
