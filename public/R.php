<?php
$title = "test";
include('../templates/header.php');
include('../config.php');

try {
    $stmt = $pdo->query('SELECT * FROM Recipe');
    $recipes = $stmt->fetchAll();
} catch (Exception $e) {
    $errorMessage = "Error fetching recipes: " . $e->getMessage();
}

function countLikes($recipeID, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Likes WHERE RecipeID = ?");
    $stmt->execute([$recipeID]);
    return $stmt->fetchColumn();
}

function countDislikes($recipeID, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Dislikes WHERE RecipeID = ?");
    $stmt->execute([$recipeID]);
    return $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: "Roboto", sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .blog_post {
            background: #fff;
            width: 100%;
            max-width: 500px;
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

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Styles for like and dislike buttons */
        .recipe button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 10px;
        }

        .recipe button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>All Recipes</h1>
    
    <form action="search.php" method="GET">
        <label for="search">Search recipes:</label>
        <input type="text" id="search" name="query">
        <button type="submit">Search</button>
    </form>
    
    <p><a href="addR.php" class="button">Add Recipe</a></p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
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
                    <form action="like_dislike.php" method="POST">
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe['RecipeID']; ?>">
                        <button type="submit" name="action" value="like">Like</button>
                        <button type="submit" name="action" value="dislike">Dislike</button>
                    </form>
                    <p>Likes: <?php echo countLikes($recipe['RecipeID'], $pdo); ?></p>
                    <p>Dislikes: <?php echo countDislikes($recipe['RecipeID'], $pdo); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
