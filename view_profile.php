<?php
session_start();
require 'auth_check.php';

$username = $_SESSION['username'];
$user     = $_SESSION['users'][$username];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - View Profile</title>
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

    <!-- CONTENT -->
    <div class="content" style="padding:0;">
        <div class="dashboard-layout">

            <?php include 'sidebar.php'; ?>

            <div class="main-panel">
                <fieldset class="profile-box">
                    <legend>PROFILE</legend>

                    <!-- Profile picture (avatar) -->
                    <?php if (!empty($user['picture'])): ?>
                        <img src="<?= htmlspecialchars($user['picture']) ?>"
                             alt="Profile" class="profile-avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">&#9786;</div>
                    <?php endif; ?>

                    <div class="profile-row">
                        <span class="profile-label">Name</span>
                        <span>:<?= htmlspecialchars($user['name']) ?></span>
                    </div>

                    <div class="profile-row">
                        <span class="profile-label">Email</span>
                        <span>:<?= htmlspecialchars($user['email']) ?></span>
                    </div>

                    <div class="profile-row">
                        <span class="profile-label">Gender</span>
                        <span>:<?= htmlspecialchars($user['gender']) ?></span>
                    </div>

                    <div class="profile-row">
                        <span class="profile-label">Date of Birth</span>
                        <span>:<?= htmlspecialchars($user['dob']) ?></span>
                        <a href="change_picture.php" class="change-link">Change</a>
                    </div>

                    <br>
                    <a href="edit_profile.php" class="edit-link">Edit Profile</a>

                </fieldset>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
