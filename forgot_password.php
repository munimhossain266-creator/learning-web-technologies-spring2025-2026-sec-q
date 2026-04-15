<?php
session_start();

// Redirect if logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $error = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $users = $_SESSION['users'] ?? [];
        $found = false;

        foreach ($users as $u) {
            if ($u['email'] === $email) {
                // In a real app we would EMAIL the password reset link.
                // For this lab, we display the password directly.
                $success = "Your password is: <strong>" . htmlspecialchars($u['password']) . "</strong>";
                $found   = true;
                break;
            }
        }

        if (!$found) {
            $error = "No account found with that email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="logo"><span class="logo-x">X</span> Company</div>
        <div class="nav">
            <a href="index.php">Home</a> |
            <a href="login.php">Login</a> |
            <a href="register.php">Registration</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <?php if ($error):   ?><p class="msg-error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="msg-success"><?= $success ?></p><?php endif; ?>

        <fieldset style="width:320px;">
            <legend>FORGOT PASSWORD</legend>

            <form method="post" action="forgot_password.php">

                <div class="form-row">
                    <label>Enter Email:</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <br>
                <button type="submit" class="btn">Submit</button>

            </form>
        </fieldset>

    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
