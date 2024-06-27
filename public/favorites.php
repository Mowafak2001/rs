<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$userID = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare('SELECT Recipe.* FROM Recipe JOIN Favorite ON Recipe.RecipeID = Favorite.RecipeID WHERE Favorite.UserID = ?');
    $stmt->execute([$userID]);
    $favorites = $stmt->fetchAll();
} catch (Exception $e) {
    $errorMessage = 'Error fetching favorites: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorite Recipes</title>
    <link rel="stylesheet" type="text/css" href="../public/css/favorites.css">
</head>
<body>
    <div class="container">
        <h1>My Favorite Recipes</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if (empty($favorites)): ?>
            <p>You have no favorite recipes.</p>
        <?php else: ?>
            <?php foreach ($favorites as $recipe): ?>
                <div class="recipe">
                    <h2><?php echo htmlspecialchars($recipe['RecipeName']); ?></h2>
                    <a href="view_recipe.php?recipe_id=<?php echo htmlspecialchars($recipe['RecipeID']); ?>" class="view-link">View Recipe</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="R.php" class="view-all-link">Back to Recipes</a>
    </div>
</body>
</html>
