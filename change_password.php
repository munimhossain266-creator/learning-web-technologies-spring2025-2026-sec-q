<?php
session_start();
require 'auth_check.php';

$username = $_SESSION['username'];
$user     = &$_SESSION['users'][$username];

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current  = $_POST['current_password']  ?? '';
    $newPass  = $_POST['new_password']       ?? '';
    $retype   = $_POST['retype_password']    ?? '';

    if (!$current || !$newPass || !$retype) {
        $error = "All fields are required.";
    } elseif ($user['password'] !== $current) {
        $error = "Current password is incorrect.";
    } elseif ($newPass !== $retype) {
        $error = "New passwords do not match.";
    } elseif (strlen($newPass) < 4) {
        $error = "New password must be at least 4 characters.";
    } else {
        // Save new password in session
        $user['password'] = $newPass;
        $success = "Password changed successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Change Password</title>
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

                <?php if ($error):   ?><p class="msg-error"><?= $error ?></p><?php endif; ?>
                <?php if ($success): ?><p class="msg-success"><?= $success ?></p><?php endif; ?>

                <fieldset style="width:340px;">
                    <legend>CHANGE PASSWORD</legend>

                    <form method="post" action="change_password.php">

                        <!-- Current Password (black label) -->
                        <div class="form-row">
                            <label class="label-current">Current Password</label> :
                            <input type="password" name="current_password">
                        </div>

                        <!-- New Password (green label) -->
                        <div class="form-row">
                            <label class="label-new">New Password</label> :
                            <input type="password" name="new_password">
                        </div>

                        <!-- Retype New Password (red label) -->
                        <div class="form-row">
                            <label class="label-retype">Retype New Password :</label>
                            <input type="password" name="retype_password">
                        </div>

                        <br>
                        <button type="submit" class="btn">Submit</button>

                    </form>
                </fieldset>

            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
