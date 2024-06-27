<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = "Home";
include('../templates/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Sharing App</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../public/css/home.css">
</head>
<body>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']); // Clear the message after displaying it
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['logout_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo $_SESSION['logout_message'];
        unset($_SESSION['logout_message']); // Clear the message after displaying it
        ?>
    </div>
<?php endif; ?>

<div class="background-container">
    <div class="centered-text">
        Welcome to Foodies
    </div>
</div>

<section class="articles">
    <article>
        <figure>
            <img src="../static/images/6.jpeg" alt="Image" />
        </figure>
        <div class="article-body">
            <h2>What is Foodies?</h2>
            <p>Foodies is a website where people who love food can share their recipes and cooking tips. It's a place to find delicious dishes, whether you're a professional chef or just like to cook at home.</p>
        </div>
    </article>
    
    <article>
        <figure>
            <img src="../static/images/3.jpeg" alt="Image" />
        </figure>
        <div class="article-body">
            <h2>Motivation</h2>
            <p>At Foodies, we believe food brings people together and makes them happy. We want to inspire you to try new things in the kitchen and share your food experiences with us.</p>
        </div>
    </article>
    
    <article>
        <figure>
            <img src="../static/images/4.jpeg" alt="Image" />
        </figure>
        <div class="article-body">
            <h2>Our Goals</h2>
            <p>Our goal is to create a friendly place where everyone can share recipes and cooking tips. We also want to help people connect with others who love food.</p>
        </div>
    </article>
</section>

<section class="about-us">
    <h2>About Us</h2>
    <p>At Foodies, we believe food is more than just a meal—it is a way to connect and share joy. Our platform is for anyone who loves to cook, whether you're a home cook or a professional chef.</p>
    <p>We started Foodies to build a community where you can discover and share recipes, cooking tips, and kitchen stories. We want to help you find inspiration, try new dishes, and connect with others who share your passion for cooking.</p>
    <p>Foodies is about more than just recipes. It's about the experiences that come with cooking and eating together. Our platform offers a range of recipes, from simple meals to gourmet dishes, ensuring there's something for everyone.</p>
    <p>Join our community to share your recipes, ask questions, and meet other food lovers. We're here to support you and make your culinary journey fun and rewarding. Welcome to Foodies!</p>
</section>
</body>
</html>
<?php include('../templates/footer.php'); ?>
