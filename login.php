<?php
session_start();

// If already logged in, go to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// --- Auto-fill username if "Remember Me" cookie exists ---
$remembered_username = $_COOKIE['remember_user'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username   = trim($_POST['username']    ?? '');
    $password   = $_POST['password']            ?? '';
    $rememberMe = isset($_POST['remember_me']);

    if (!$username || !$password) {
        $error = "Please enter username and password.";
    } else {
        $users = $_SESSION['users'] ?? [];

        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            // --- Login success: store user info in session ---
            $_SESSION['username'] = $username;
            $_SESSION['name']     = $users[$username]['name'];

            // --- Handle Remember Me cookie ---
            if ($rememberMe) {
                // Cookie lasts 30 days
                setcookie('remember_user', $username, time() + (30 * 24 * 3600), '/');
            } else {
                // Clear any existing cookie
                setcookie('remember_user', '', time() - 3600, '/');
            }

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Login</title>
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

        <?php if ($error): ?><p class="msg-error"><?= $error ?></p><?php endif; ?>

        <fieldset style="width:320px;">
            <legend>LOGIN</legend>

            <form method="post" action="login.php">

                <!-- Username -->
                <div class="form-row">
                    <label>User Name :</label>
                    <input type="text" name="username"
                           value="<?= htmlspecialchars($remembered_username) ?>">
                </div>

                <!-- Password -->
                <div class="form-row">
                    <label>Password &nbsp;:</label>
                    <input type="password" name="password">
                </div>

                <!-- Remember Me -->
                <div class="remember-row">
                    <input type="checkbox" name="remember_me"
                           <?= $remembered_username ? 'checked' : '' ?>>
                    Remember Me
                </div>

                <br>
                <button type="submit" class="btn">Submit</button>
                <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>

            </form>
        </fieldset>

    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
