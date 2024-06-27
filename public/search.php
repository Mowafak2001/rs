<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

$query = isset($_GET['query']) ? $_GET['query'] : '';

try {
    // Fetch recipes that match the search query
    $stmt = $pdo->prepare('SELECT * FROM Recipe WHERE RecipeName LIKE ?');
    $stmt->execute(['%' . $query . '%']);
    $recipes = $stmt->fetchAll();
} catch (Exception $e) {
    $errorMessage = "Error fetching recipes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addR.css">
    <style>
        body {
            font-family: "Roboto", sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-results {
            width: 80%;
            max-width: 1000px;
            margin-top: 20px;
        }

        .blog_post {
            background: #fff;
            width: 100%;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 1px 1px 2rem rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .container_copy {
            padding: 2rem;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.5;
            color: #666;
            margin-bottom: 1rem;
        }

        .btn_primary, .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            box-shadow: 1px 10px 2rem rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in;
            margin-right: 10px;
        }

        .btn_primary:hover, .button:hover {
            background-color: #0056b3;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .view-all-link {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .view-all-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="center-content">
    <div class="search-results">
        <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
        <a href="R.php" class="view-all-link">Back to Recipes</a>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <?php if (empty($recipes)): ?>
            <p>No recipes found.</p>
        <?php else: ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="blog_post">
                    <div class="container_copy">
                        <h1><?php echo htmlspecialchars($recipe['RecipeName']); ?></h1>
                        <p><?php echo nl2br(htmlspecialchars($recipe['Ingredients'])); ?></p>
                        <a href="view_recipe.php?recipe_id=<?php echo $recipe['RecipeID']; ?>" class="btn_primary">View</a>
                        <a href="edit_recipe.php?recipe_id=<?php echo $recipe['RecipeID']; ?>" class="btn_primary">Edit</a>
                        <a href="remove_recipe.php?recipe_id=<?php echo $recipe['RecipeID']; ?>" class="btn_primary">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
