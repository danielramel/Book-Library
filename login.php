<?php
session_start();

require_once "alap/auth.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $auth = new Auth();

    $user = $auth->check_credentials($username, $password);

    if (empty($username) || empty($password)) {
        $error = "Minden mezőt ki kell tölteni!";
    } else if ($user) {
        
        $auth->login($user);
        header("Location: index.php");
        exit;
    } else {
        $error = "Hibás felhasználónév vagy jelszó!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>IK-Library | Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/forms.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > Bejelentkezés</h1>
        <h3><a href="register.php">Regisztráció</a></h3>
    </header>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <div id="content">
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username"><br>
            <label>Password:</label><br>
            <input type="password" name="password"><br>
            <button type="submit">Sign in</button>
        </form>
    </div>
    <footer>
        <p>&copy; IK-Library, 2021</p>
    </footer>
</body>
</html>