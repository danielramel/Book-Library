<?php 
session_start();
require_once "alap/jsonstorage.php";
$books = new JsonStorage("data/books.json");
$users = new JsonStorage("data/users.json");

foreach ($users->all() as $user) {
    if ($user->username === $_GET["name"]) {
        break;
    }
}

$ratings = [];
foreach ($books->all() as $book) {
    if (isset($book->ratings)) {
        foreach ($book->ratings as $rating) {
            if ($rating->user === $user->username) {
                $ratings[] = [$book->title, $rating->description];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IK-Library | User</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="content" style="min-height:40vh;">
        <div id="card-list">
            <div class="book-card">
                    <h2>Username: <?php echo $user->username; ?></h2>
                    <h2>Email: <?php echo $user->email; ?></h2>
                    <h2>Last login: <?php echo $user->last_login; ?></h2>
            </div>
        </div>
    </div>
    <div id="content" style="min-height:40vh;">
    <?php if(count($ratings) > 0) : ?>
        <div id="card-list">
                <?php foreach ($ratings as $rating): ?>
                    <div class="book-card">
                        <h3><?php echo $rating[0] ?></h3>
                        <p><?php echo $rating[1] ?></p>
                    </div>
                <?php endforeach; ?>
        </div>
    
    <?php endif; ?>
    </div>
    <footer>
        <p>&copy; IK-Library, 2021</p>
    </footer>
</body>
