<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $userID = $_SESSION['user_id']; // Assuming you store user ID in session

    // Handle file upload
    $imagePath = '';
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['recipe_image']['tmp_name'];
            $fileName = $_FILES['recipe_image']['name'];
            $fileSize = $_FILES['recipe_image']['size'];
            $fileType = $_FILES['recipe_image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = 'F:/asd/htdocs/rs/uploads/';
                $dest_path = $uploadFileDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imagePath = 'uploads/' . $fileName;
                } else {
                    error_log('Error moving file: ' . print_r($_FILES['recipe_image'], true));
                    $_SESSION['error_message'] = 'There was an error moving the uploaded file. Please check the folder permissions.';
                    header("Location: addR.php");
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                header("Location: addR.php");
                exit();
            }
        } else {
            error_log('File upload error code: ' . $_FILES['recipe_image']['error']);
            $_SESSION['error_message'] = 'There was an error uploading the file.';
            header("Location: addR.php");
            exit();
        }
    }

    $submissionDate = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare('INSERT INTO Recipe (RecipeName, Ingredients, Steps, Image, UserID, SubmissionDate) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $ingredients, $instructions, $imagePath, $userID, $submissionDate]);

        $_SESSION['success_message'] = 'Recipe added successfully!';
        header("Location: addR.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error adding recipe: ' . $e->getMessage();
        header("Location: addR.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../public/css/addR.css">
    <title>Add Recipe</title>
</head>
<body class="center-content">
    <div>
        <h1>Add Recipe</h1>
        <a href="R.php" class="view-all-link">View All Recipes</a>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        <?php endif; ?>
        <form action="addR.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="ingredients">Ingredients:</label><br>
            <textarea id="ingredients" name="ingredients" required></textarea><br>
            <label for="instructions">Instructions:</label><br>
            <textarea id="instructions" name="instructions" required></textarea><br>
            <label for="recipe_image">Upload Image (optional):</label><br>
            <input type="file" id="recipe_image" name="recipe_image" accept="image/*"><br>
            <input type="submit" value="Submit">
            <a href="R.php" class="view-all-link">Go back?</a>
        </form>
    </div>
</body>
</html>
