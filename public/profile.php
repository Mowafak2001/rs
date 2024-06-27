<?php
session_start();
include('../config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$userID = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM User WHERE UserID = ?");
    $stmt->execute([$userID]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    $errorMessage = "Error fetching profile: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="../public/css/profile.css">
</head>
<body>
    <?php include('../templates/header.php'); ?>
    <div class="form-container">
        <h1>User Profile</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Profile Picture:</label><br>
            <?php if ($user['ProfilePicture']): ?>
                <img src="../uploads/<?php echo htmlspecialchars($user['ProfilePicture']); ?>" alt="Profile Picture" width="100"><br>
            <?php endif; ?>
            <input type="file" id="profile_picture" name="profile_picture"><br>

            <label for="bio">Bio:</label><br>
            <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['Bio']); ?></textarea><br>

            <input type="submit" value="Update Profile">
        </form>
    </div>
    <?php include('../templates/footer.php'); ?>
</body>
</html>
