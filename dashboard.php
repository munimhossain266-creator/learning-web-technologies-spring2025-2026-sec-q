<?php
session_start();
require 'auth_check.php';

$username = $_SESSION['username'];
$name     = $_SESSION['users'][$username]['name'] ?? $username;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="logo"><span class="logo-x">X</span> Company</div>
        <div class="nav">
            Logged in as <a href="view_profile.php"><?= htmlspecialchars($username) ?></a> |
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- CONTENT: two-column layout -->
    <div class="content" style="padding:0;">
        <div class="dashboard-layout">

            <?php include 'sidebar.php'; ?>

            <div class="main-panel">
                <p><strong>Welcome <?= htmlspecialchars($name) ?></strong></p>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
