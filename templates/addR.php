
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="../public/css/addR.css">
<title>Add Recipe</title>
</head>
<body class="center-content">
    <div>
        <h1>Add Recipe</h1>
        <a href="/recipes" class="view-all-link">View All Recipes</a>
        <p style="color: red;"></p>
        
        <p style="color: green;"></p>
        
        <form action="/add_recipe" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="ingredients">Ingredients:</label><br>
            <textarea id="ingredients" name="ingredients" required></textarea><br>
            <label for="instructions">Instructions:</label><br>
            <textarea id="instructions" name="instructions" required></textarea><br>
            <label for="recipe_image">Upload Image:</label><br>
            <input type="file" id="recipe_image" name="recipe_image" accept="image/*"><br>
            <input type="submit" value="Submit">
            <a href="../templates/R.php" class="view-all-link">Go back?</a>
        </form>
    </div>
</body>
</html>