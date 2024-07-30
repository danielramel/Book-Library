<header>
        <h1><a href="index.php">IK-Library</a> > Home</h1>

        <?php if (isset($_SESSION["user"])): ?>
                <h1>Welcome, <a href='user.php?name=<?php echo $_SESSION["user"]["username"];?>'><?php echo $_SESSION["user"]["username"]; ?></a>!</h1>
        <?php endif; ?>
        <div id="menu">
                <?php if (isset($_SESSION["user"])): ?>
                        <h3><a href="logout.php">Sign out</a></h3>
                        <?php if ($_SESSION["user"]["username"] === "admin"): ?>
                        <h3><a href="create_book.php">Create book</a></h3>
                        <?php endif; ?>
                <?php else: ?>
                        <h3><a href="login.php">Sign in</a></h3>
                        <h3><a href="register.php">Register</a></h3>
                <?php endif; ?>
        </div>
</header>