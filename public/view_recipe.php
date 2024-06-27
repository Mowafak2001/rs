<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../config.php');

if (!isset($_GET['recipe_id'])) {
    header("Location: R.php");
    exit();
}

$recipeID = $_GET['recipe_id'];
$userID = $_SESSION['user_id']; // Assuming you store user ID in session

// Handle form submission for rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = $_POST['rating'];
    try {
        $stmt = $pdo->prepare('INSERT INTO Rating (UserID, RecipeID, Rating) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE Rating = VALUES(Rating)');
        $stmt->execute([$userID, $recipeID, $rating]);
        $_SESSION['success_message'] = 'Rating submitted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error submitting rating: ' . $e->getMessage();
    }
}

// Handle form submission for review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_text'])) {
    $reviewText = $_POST['review_text'];
    $reviewDate = date('Y-m-d H:i:s');
    try {
        $stmt = $pdo->prepare('INSERT INTO Review (UserID, RecipeID, ReviewText, ReviewDate) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userID, $recipeID, $reviewText, $reviewDate]);
        $_SESSION['review_success_message'] = 'Review submitted successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error submitting review: ' . $e->getMessage();
    }
}

// Handle favorite functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite'])) {
    if ($_POST['favorite'] === 'add') {
        try {
            $stmt = $pdo->prepare('INSERT INTO Favorite (UserID, RecipeID) VALUES (?, ?)');
            $stmt->execute([$userID, $recipeID]);
            $_SESSION['success_message'] = 'Recipe added to favorites!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error adding to favorites: ' . $e->getMessage();
        }
    } elseif ($_POST['favorite'] === 'remove') {
        try {
            $stmt = $pdo->prepare('DELETE FROM Favorite WHERE UserID = ? AND RecipeID = ?');
            $stmt->execute([$userID, $recipeID]);
            $_SESSION['success_message'] = 'Recipe removed from favorites!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error removing from favorites: ' . $e->getMessage();
        }
    }
}

try {
    $stmt = $pdo->prepare('SELECT * FROM Recipe WHERE RecipeID = ?');
    $stmt->execute([$recipeID]);
    $recipe = $stmt->fetch();

    $stmt = $pdo->prepare('SELECT Rating FROM Rating WHERE RecipeID = ?');
    $stmt->execute([$recipeID]);
    $ratings = $stmt->fetchAll();
    $averageRating = $stmt->rowCount() ? array_sum(array_column($ratings, 'Rating')) / count($ratings) : 'No ratings yet';

    $stmt = $pdo->prepare('SELECT Review.*, User.FirstName, User.LastName FROM Review JOIN User ON Review.UserID = User.UserID WHERE RecipeID = ?');
    $stmt->execute([$recipeID]);
    $reviews = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM Favorite WHERE UserID = ? AND RecipeID = ?');
    $stmt->execute([$userID, $recipeID]);
    $isFavorite = $stmt->rowCount() > 0;
} catch (Exception $e) {
    $errorMessage = 'Error fetching recipe: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Recipe</title>
    <link rel="stylesheet" type="text/css" href="../public/css/view_recipe.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($recipe['RecipeName']); ?></h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        <?php endif; ?>
        <?php if ($recipe['Image']): ?>
            <img src="../<?php echo htmlspecialchars($recipe['Image']); ?>" alt="Recipe Image">
        <?php endif; ?>
        <p><strong>Ingredients:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($recipe['Ingredients'])); ?></p>
        <p><strong>Instructions:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($recipe['Steps'])); ?></p>
        <a href="R.php" class="view-all-link">Back to Recipes</a>

        <div class="rating-form">
            <h2>Rate this Recipe</h2>
            <form action="view_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" method="post">
                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <input type="submit" value="Submit Rating">
            </form>
            <p><strong>Average Rating:</strong> <?php echo $averageRating; ?></p>
        </div>

        <div class="review-form">
            <h2>Add a Review</h2>
            <?php if (isset($_SESSION['review_success_message'])): ?>
                <p style="color: green;"><?php echo $_SESSION['review_success_message']; unset($_SESSION['review_success_message']); ?></p>
            <?php endif; ?>
            <form action="view_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" method="post">
                <textarea name="review_text" required></textarea>
                <input type="submit" value="Submit Review">
            </form>
        </div>

        <div class="reviews">
            <h2>Reviews</h2>
            <?php if (empty($reviews)): ?>
                <p>No reviews yet. Be the first to review!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <p class="user"><?php echo htmlspecialchars($review['FirstName'] . ' ' . $review['LastName']); ?></p>
                        <p class="date"><?php echo htmlspecialchars($review['ReviewDate']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($review['ReviewText'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="favorite-form">
            <h2>Favorite this Recipe</h2>
            <form action="view_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" method="post">
                <?php if ($isFavorite): ?>
                    <input type="hidden" name="favorite" value="remove">
                    <input type="submit" value="Remove from Favorites">
                <?php else: ?>
                    <input type="hidden" name="favorite" value="add">
                    <input type="submit" value="Add to Favorites">
                <?php endif; ?>
            </form>
        </div>

        <a href="edit_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" class="view-all-link">Edit Recipe</a>
        <a href="remove_recipe.php?recipe_id=<?php echo htmlspecialchars($recipeID); ?>" class="view-all-link" onclick="return confirm('Are you sure you want to remove this recipe?');">Remove Recipe</a>
    </div>
</body>
</html>
