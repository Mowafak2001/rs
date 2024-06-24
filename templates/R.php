<?php
$title = "test";
include('../templates/header.php');
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
            background: #fff; /* Default background, will be overridden by JS */
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
    </style>
</head>
<body>
    <h1>All Recipes</h1>
    
    <form action="/search" method="GET">
        <label for="search">Search recipes:</label>
        <input type="text" id="search" name="query">
        <button type="submit">Search</button>
    </form>
    
    <p><a href="../templates/addR.php" class="button">Add Recipe</a></p>
    
    <div class="blog_post">
        <div class="container_copy">
            <h1>test</h1>
            <p> test</p>
            <a href="{{ url_for('view_recipe_detail', recipe_id=recipe.id) }}" class="btn_primary">View</a>
            <a href="/edit_recipe/{{ recipe.id }}" class="btn_primary">Edit</a>
            <a href="/remove_recipe/{{ recipe.id }}" class="btn_primary">Remove</a>
        </div>
    </div>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const colors = ['#ffadad', '#ffd6a5', '#fdffb6', '#caffbf', '#9bf6ff', '#a0c4ff', '#bdb2ff', '#ffc6ff'];
            document.querySelectorAll('.blog_post').forEach(function(card) {
                const colorIndex = Math.floor(Math.random() * colors.length);
                card.style.backgroundColor = colors[colorIndex];
            });
        });
    </script>
</body>
</html>