<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="logo"><span class="logo-x">X</span> Company</div>
        <div class="nav">
            <?php if (isset($_SESSION['username'])): ?>
                Logged in as <a href="dashboard.php"><?= htmlspecialchars($_SESSION['username']) ?></a> |
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a> |
                <a href="login.php">Login</a> |
                <a href="register.php">Registration</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <p><strong>Welcome to xCompany</strong></p>
    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
