<?php
session_start();

$form = [
    "username" => "",
    "email" => "",
    "password" => "",
    "password2" => ""
];

require_once "alap/auth.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    $form = [
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "password2" => $password2
    ];
    $auth = new Auth();

    if (empty($username) || empty($email) || empty($password) || empty($password2)) {
        $error = "All fields are required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    } else if ($password !== $password2) {
        $error = "Passwords do not match!";
    } else if ($auth->user_exists($username)) {
        $error = "Username is already taken!";
    } else {
        $auth->register(["username" => $username, "password" => $password, "email" => $email, "last_login" => date("Y-m-d H:i:s")]);
        $user = $auth->check_credentials($username, $password);
        $auth->login($user);
        header("Location: index.php");
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IK-Library | Register</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/forms.css">

</head>
<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > Register</h1>
        <h3><a href="login.php">Sign in</a></h3>
    </header>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <div id="content">
        <h1>Register</h1>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($form['username']); ?>"><br>
            <label>Email:</label><br>
            <input type="text" name="email" value="<?php echo htmlspecialchars($form['email']); ?>"><br>
            <label>Password:</label><br>
            <input type="password" name="password" value="<?php echo htmlspecialchars($form['password']); ?>"><br>
            <label>Password again:</label><br>
            <input type="password" name="password2" value="<?php echo htmlspecialchars($form['password2']); ?>"><br>
            <button type="submit">Register</button>
        </form>
        
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>
</html>
