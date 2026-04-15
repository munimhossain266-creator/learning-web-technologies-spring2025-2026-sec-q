<?php
session_start();
require 'auth_check.php';

$username = $_SESSION['username'];
$user     = &$_SESSION['users'][$username];

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['picture']) || $_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = "Please choose a file to upload.";
    } elseif ($_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload error. Please try again.";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $ftype        = $_FILES['picture']['type'];

        if (!in_array($ftype, $allowedTypes)) {
            $error = "Only JPG, PNG, or GIF images are allowed.";
        } elseif ($_FILES['picture']['size'] > 2 * 1024 * 1024) {
            $error = "File size must not exceed 2 MB.";
        } else {
            // Convert image to base64 data URI so we can store it in session
            // (In production you would save to disk instead)
            $imageData   = file_get_contents($_FILES['picture']['tmp_name']);
            $base64      = base64_encode($imageData);
            $dataUri     = "data:{$ftype};base64,{$base64}";

            $user['picture'] = $dataUri;
            $success = "Profile picture updated!";
        }
    }
}

$currentPic = $user['picture'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Profile Picture</title>
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

                <fieldset style="width:300px;">
                    <legend>PROFILE PICTURE</legend>

                    <!-- Show current picture or placeholder -->
                    <?php if ($currentPic): ?>
                        <img src="<?= $currentPic ?>" alt="Profile"
                             style="width:80px; height:80px; border-radius:50%; object-fit:cover; display:block; margin-bottom:10px;">
                    <?php else: ?>
                        <div style="width:80px; height:80px; background:#ddd; border-radius:50%;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:36px; color:#666; margin-bottom:10px;">&#9786;</div>
                    <?php endif; ?>

                    <!-- Upload form — must use enctype for file upload -->
                    <form method="post" action="change_picture.php" enctype="multipart/form-data">
                        <input type="file" name="picture" accept="image/*">
                        <br><br>
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
