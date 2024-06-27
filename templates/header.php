<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Foodies'; ?></title>
    <!-- Link to external CSS file for navbar styling -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="navbar"> <!-- Navigation bar container -->
        <div class="nav-logo"> <!-- Logo section -->
            <a href="index.php">Foodies</a> <!-- Logo link -->
        </div>
        <div class="nav-items" id="nav-items"> <!-- Navigation items section -->
            <ul> <!-- Unordered list for navigation items -->
                <?php if ($isLoggedIn): ?>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="R.php">Recipes</a></li>
                    <li><a href="favorites.php">Favorites</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Log out</a></li>
                <?php else: ?>
                    <li><a href="contactus.php">Contact Us</a></li>
                    <li><a href="signin.php">Sign in</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div id="hamburger-menu">&#9776;</div> <!-- Hamburger menu icon -->
    </div>

    <script>
    // JavaScript code for responsive navbar
    document.addEventListener("DOMContentLoaded", function() {
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const navItems = document.getElementById('nav-items');

        hamburgerMenu.addEventListener('click', function() {
            if (navItems.style.display === 'block') {
                navItems.style.display = 'none';
            } else {
                navItems.style.display = 'block';
            }
        });
    });
    </script>
</body>
</html>
