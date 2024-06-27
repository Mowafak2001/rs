<?php
session_start();
include('../config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$userID = $_SESSION['user_id'];
$updateError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $profilePicture = $_FILES['profile_picture'];

    // Handling profile picture upload
    if ($profilePicture['name']) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($profilePicture["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($profilePicture["tmp_name"]);
        if($check === false) {
            $updateError = "File is not an image.";
        }
        
        // Check file size (limit to 2MB)
        if ($profilePicture["size"] > 2000000) {
            $updateError = "Sorry, your file is too large.";
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $updateError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Check if $updateError is set to an error
        if ($updateError == '') {
            if (move_uploaded_file($profilePicture["tmp_name"], $targetFile)) {
                // Update database with new profile picture and bio
                try {
                    $stmt = $pdo->prepare("UPDATE User SET Bio = ?, ProfilePicture = ? WHERE UserID = ?");
                    $stmt->execute([$bio, basename($profilePicture["name"]), $userID]);
                    $_SESSION['success_message'] = "Profile updated successfully!";
                    header("Location: profile.php");
                    exit;
                } catch (Exception $e) {
                    $updateError = "Error updating profile: " . $e->getMessage();
                }
            } else {
                $updateError = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // Update database with new bio only
        try {
            $stmt = $pdo->prepare("UPDATE User SET Bio = ? WHERE UserID = ?");
            $stmt->execute([$bio, $userID]);
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit;
        } catch (Exception $e) {
            $updateError = "Error updating profile: " . $e->getMessage();
        }
    }
}

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

        <?php if ($updateError): ?>
            <div class="error"><?php echo htmlspecialchars($updateError); ?></div>
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
