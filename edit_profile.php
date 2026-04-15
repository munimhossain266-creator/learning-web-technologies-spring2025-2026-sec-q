<?php
session_start();
require 'auth_check.php';

$username = $_SESSION['username'];
$user     = &$_SESSION['users'][$username];  // reference so changes persist

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = trim($_POST['name']   ?? '');
    $email  = trim($_POST['email']  ?? '');
    $gender = $_POST['gender']          ?? '';
    $dd     = trim($_POST['dob_dd']  ?? '');
    $mm     = trim($_POST['dob_mm']  ?? '');
    $yyyy   = trim($_POST['dob_yyyy']?? '');

    if (!$name || !$email || !$gender || !$dd || !$mm || !$yyyy) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check email uniqueness (exclude current user)
        foreach ($_SESSION['users'] as $uname => $u) {
            if ($uname !== $username && $u['email'] === $email) {
                $error = "Email already used by another account.";
                break;
            }
        }

        if (!$error) {
            // Save changes into session
            $user['name']   = $name;
            $user['email']  = $email;
            $user['gender'] = $gender;
            $user['dob']    = "$dd/$mm/$yyyy";

            $success = "Profile updated successfully!";
        }
    }
}

// Re-read (in case just updated)
$u = $_SESSION['users'][$username];
list($cur_dd, $cur_mm, $cur_yyyy) = explode('/', $u['dob']) + ['', '', ''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Edit Profile</title>
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

                <fieldset>
                    <legend>EDIT PROFILE</legend>

                    <form method="post" action="edit_profile.php">

                        <!-- Name -->
                        <div class="form-row">
                            <label>Name</label>:
                            <input type="text" name="name"
                                   value="<?= htmlspecialchars($u['name']) ?>">
                        </div>

                        <!-- Email -->
                        <div class="form-row">
                            <label>Email</label>:
                            <input type="email" name="email"
                                   value="<?= htmlspecialchars($u['email']) ?>">
                            <span style="margin-left:4px; font-style:italic;">i</span>
                        </div>

                        <!-- Gender -->
                        <div class="form-row">
                            <label>Gender</label>:
                            <label><input type="radio" name="gender" value="Male"
                                <?= ($u['gender'] === 'Male'   ? 'checked' : '') ?>> Male</label>&nbsp;
                            <label><input type="radio" name="gender" value="Female"
                                <?= ($u['gender'] === 'Female' ? 'checked' : '') ?>> Female</label>&nbsp;
                            <label><input type="radio" name="gender" value="Other"
                                <?= ($u['gender'] === 'Other'  ? 'checked' : '') ?>> Other</label>
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-row">
                            <label>Date of Birth</label>:
                            <div>
                                <input type="text" name="dob_dd"   maxlength="2" style="width:30px;"
                                       value="<?= htmlspecialchars($cur_dd) ?>">
                                /
                                <input type="text" name="dob_mm"   maxlength="2" style="width:30px;"
                                       value="<?= htmlspecialchars($cur_mm) ?>">
                                /
                                <input type="text" name="dob_yyyy" maxlength="4" style="width:50px;"
                                       value="<?= htmlspecialchars($cur_yyyy) ?>">
                                <br><span style="font-size:11px; color:#888; font-style:italic;">(dd/mm/yyyy)</span>
                            </div>
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
