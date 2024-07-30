<?php
session_start();
require_once "alap/jsonstorage.php";
$storage = new JsonStorage("data/books.json");
$books = $storage->all();

$id = $_GET["id"];
$book = $books[$id];
$avg_rating = 0;
$ratings = $book->ratings ?? [];
if (count($ratings) !== 0) {
    $sum = 0;
    foreach ($ratings as $rating) {
        $sum += $rating->rating;
    }
    $avg_rating = $sum / count($ratings);
}

if (!isset($_SESSION["old"])) {
    $_SESSION["old"] = [
        "rating" => "",
        "description" => ""
    ];
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION["old"]);
    $rating = $_POST["rating"];
    $description = $_POST["description"];

    if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 10){
        $_SESSION["error"] = "Invalid rating!";
        $_SESSION["old"] = [
            "rating" => $rating,
            "description" => $description
        ];
        header("Location: book.php?id=$id");
        exit;
    }

    $ratings[] = (object) [
        "rating" => $rating,
        "description" => $description,
        "user" => $_SESSION["user"]["username"]
    ];

    $storage->update(
        function ($elem) use ($id) {
            return $elem->_id === $id;
        },
        function (&$elem) use ($ratings) {
            $elem->ratings = $ratings;
        }
    );

    header("Location: book.php?id=$id");

}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Details: <?php echo $book->title; ?></title>
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        .horizontal {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10%;
            
        }
        .horizontal form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <?php if (isset($_SESSION["error"])): ?>
        <h1 style="color: red;"><?php echo $_SESSION["error"]; ?></h1>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>
    <div id="content" style="min-height:40vh;">
        <div class="horizontal">
            <div class="book-card">
                <img src="<?php echo $book->image; ?>" alt="">
                    <h3><?php echo $book->title; ?></h3>
                    <h2>-</h2>
                    <h2><?php echo $book->author; ?></h2>
                    <p><?php echo $book->publisher; ?></p>
                    <p><?php echo $book->year; ?></p>
                    <p><?php echo $book->genre; ?></p>
            </div>
            <?php if (!isset($_SESSION["user"])): ?>
                <h3>You need to be logged in to rate this book!</h3>
            <?php else: ?>
            <form action="" method="post">
                <label for="">Rating (1-10)</label>
                <input type="number" name="rating" value=<?php echo $_SESSION['old']['rating']; ?>>
                <label for="">Description</label>
                <textarea name="description" id="" cols="30" rows="10"><?php echo $_SESSION["old"]["description"] ?></textarea>
                <button type="submit">Rate</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <div id="content" style="min-height:40vh;">
    <?php if (isset($book->ratings)): ?>
        <h3 style="text-align:center" >Average Rating: <?php echo round($avg_rating,2)?> </h3>
        <div id="card-list">
                <?php foreach ($book->ratings as $rating): ?>
                    <div class="book-card">
                        <h3><?php echo $rating->rating; ?></h3>
                        <p><?php echo $rating->description; ?></p>
                        <p style="font-style: italic;"><?php echo $rating->user; ?></p>
                    </div>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>
    </div>
    
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>
</html>
