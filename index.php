<?php
session_start();

require_once "alap/jsonstorage.php";

$storage = new JsonStorage("data/books.json");
$books = $storage->all();

if (isset($_GET["genre"])) {
    $books = array_filter($books, function($book) {
        return strpos($book->genre, $_GET["genre"]) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IK-Library | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="content">
        <form style="text-align:center;" action="index.php" method="GET">
            <h2> Filter by genre: </h2>
            <input type="text" name="genre">
            <input type="submit" value="Filter">

        </form>

        <div id="card-list">
            <?php if (count($books) !== 0): 
                foreach($books as $index => $book): ?>
                    <div class="book-card">
                        <div class="image">
                            <img src="<?php echo $book->image; ?>" alt="">
                        </div>
                            <h2><a href="book.php?id=<?php echo $index; ?>"><?php echo $book->author .  " -<br>" .$book->title; ?></a></h2>
                        <?php if (isset($_SESSION["user"]) && $_SESSION["user"]["username"] === "admin"): ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>

</html>