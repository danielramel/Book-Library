<?php
session_start();

require_once "alap/auth.php";
$auth = new Auth();

if (!$auth->is_authenticated() || $_SESSION["user"]["username"] !== "admin") {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "alap/jsonstorage.php";
    $storage = new JsonStorage("data/books.json");

    if (empty($_POST["title"]) || empty($_POST["author"]) || empty($_POST["publisher"]) || empty($_POST["year"]) || empty($_POST["genre"]) || empty($_POST["image"])) {
        $_SESSION["error"] = "All fields are required!";
        header("Location: create_book.php");
        exit;
    }
    $storage->insert((object) $_POST);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
    
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin: Create Book</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <style>
        .image-selection {
            display: flex;
            gap: 20px;
        }

        .image-selection input[type="radio"] {
            display: none;
        }

        .image-selection label {
            cursor: pointer;
        }

        .image-selection label img {
            border: 2px solid transparent;
            border-radius: 5px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .image-selection input[type="radio"]:checked + label img {
            border-color: blue;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > Admin: Create Book</h1>
        <h1>Welcome, admin!</h1>
        <div id="menu">
            <h3><a href="logout.php">Sign out</a></h3>
        </div>
    </header>
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>
    <div id="content">
        <h2>Create a new book</h2>
        <form method="post">
            <label>Title:</label><br>
            <input type="text" name="title"><br>
            <label>Author:</label><br>
            <input type="text" name="author"><br>
            <label>Publisher:</label><br>
            <input name="publisher"></input><br>
            <label>Year published:</label><br>
            <input type="text" name="year"><br>
            <label>Genre:</label><br>
            <input name="genre"></input><br>
            
            <label>Select Cover:</label><br>
            <div class="image-selection">
                <input type="radio" id="image1" name="image" value="assets/book_cover_1.png">
                <label for="image1">
                    <img src="assets\book_cover_1.png" alt="Image 1">
                </label>

                <input type="radio" id="image2" name="image" value="assets/book_cover_2.png">
                <label for="image2">
                    <img src="assets\book_cover_2.png" alt="Image 2">
                </label>

                <input type="radio" id="image3" name="image" value="assets/book_cover_3.png">
                <label for="image3">
                    <img src="assets\book_cover_3.png" alt="Image 3">
                </label>
                <input type="radio" id="image4" name="image" value="assets/book_cover_4.png">
                <label for="image4">
                    <img src="assets\book_cover_4.png" alt="Image 4">
                </label>
                <input type="radio" id="image5" name="image" value="assets/book_cover_5.png">
                <label for="image5">
                    <img src="assets\book_cover_5.png" alt="Image 5">
                </label>
                <input type="radio" id="image6" name="image" value="assets/book_cover_6.png">
                <label for="image6">
                    <img src="assets\book_cover_6.png" alt="Image 6">
                </label>
            </div>
            <button type="submit">Create book</button>
        </form>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>
</html>
