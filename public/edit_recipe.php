<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

if (!isset($_GET['recipe_id'])) {
    // Redirect to recipes page if no recipe_id is provided
    header("Location: R.php");
    exit();
}

$recipeID = $_GET['recipe_id'];

try {
    // Fetch the recipe details
    $stmt = $pdo->prepare('SELECT * FROM Recipe WHERE RecipeID = ?');
    $stmt->execute([$recipeID]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        // Redirect to recipes page if recipe not found
        header("Location: R.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $ingredients = $_POST['ingredients'];
        $instructions = $_POST['instructions'];

        // Handle image upload if a new image is provided
        $image = $recipe['Image'];
        if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
            $image = 'uploads/' . basename($_FILES['recipe_image']['name']);
            move_uploaded_file($_FILES['recipe_image']['tmp_name'], $image);
        }

        // Update the recipe details
        $stmt = $pdo->prepare('UPDATE Recipe SET RecipeName = ?, Ingredients = ?, Steps = ?, Image = ? WHERE RecipeID = ?');
        $stmt->execute([$name, $ingredients, $instructions, $image, $recipeID]);

        $_SESSION['success_message'] = "Recipe updated successfully!";
        header("Location: view_recipe.php?recipe_id=" . $recipeID);
        exit();
    }
} catch (Exception $e) {
    $errorMessage = "Error updating recipe: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="../public/css/addR.css">
<title>Edit Recipe</title>
</head>
<body class="center-content">
    <div>
        <h1>Edit Recipe</h1>
        <a href="R.php" class="view-all-link">View All Recipes</a>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form action="edit_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($recipe['RecipeName']); ?>" required><br>
            <label for="ingredients">Ingredients:</label><br>
            <textarea id="ingredients" name="ingredients" required><?php echo htmlspecialchars($recipe['Ingredients']); ?></textarea><br>
            <label for="instructions">Instructions:</label><br>
            <textarea id="instructions" name="instructions" required><?php echo htmlspecialchars($recipe['Steps']); ?></textarea><br>
            <label for="recipe_image">Upload Image:</label><br>
            <input type="file" id="recipe_image" name="recipe_image" accept="image/*"><br>
            <input type="submit" value="Update">
            <a href="R.php" class="view-all-link">Go back?</a>
        </form>
    </div>
</body>
</html>
