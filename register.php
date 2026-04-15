<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Collect and sanitize inputs ---
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']         ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $gender   = $_POST['gender']           ?? '';
    $dd       = trim($_POST['dob_dd']  ?? '');
    $mm       = trim($_POST['dob_mm']  ?? '');
    $yyyy     = trim($_POST['dob_yyyy']?? '');
    $dob      = "$dd/$mm/$yyyy";

    // --- Basic Validation ---
    if (!$name || !$email || !$username || !$password || !$confirm || !$gender || !$dd || !$mm || !$yyyy) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        // --- Check username/email not already taken (stored in session-based "database") ---
        $users = $_SESSION['users'] ?? [];

        $taken = false;
        foreach ($users as $u) {
            if ($u['username'] === $username) { $error = "Username already taken."; $taken = true; break; }
            if ($u['email']    === $email)    { $error = "Email already registered."; $taken = true; break; }
        }

        if (!$taken) {
            // --- Store new user ---
            // We use username as the key for easy lookup
            $_SESSION['users'][$username] = [
                'name'     => $name,
                'email'    => $email,
                'username' => $username,
                'password' => $password,   // plain text (lab exercise — not for production!)
                'gender'   => $gender,
                'dob'      => $dob,
                'picture'  => null,        // no profile picture yet
            ];

            $success = "Registration successful! <a href='login.php'>Login here</a>.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>xCompany - Registration</title>
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

        <fieldset>
            <legend>REGISTRATION</legend>

            <form method="post" action="register.php">

                <!-- Name -->
                <div class="form-row">
                    <label>Name</label>:
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>

                <!-- Email -->
                <div class="form-row">
                    <label>Email</label>:
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <span style="margin-left:4px; font-style:italic;">i</span>
                </div>

                <!-- Username -->
                <div class="form-row">
                    <label>User Name</label>:
                    <input type="text" name="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>

                <!-- Password -->
                <div class="form-row">
                    <label>Password</label>:
                    <input type="password" name="password">
                </div>

                <!-- Confirm Password -->
                <div class="form-row">
                    <label>Confirm Password</label>:
                    <input type="password" name="confirm_password">
                </div>

                <!-- Gender -->
                <fieldset class="gender-group">
                    <legend>Gender</legend>
                    <label><input type="radio" name="gender" value="Male"
                        <?= (($_POST['gender'] ?? '') === 'Male'   ? 'checked' : '') ?>> Male</label>
                    <label><input type="radio" name="gender" value="Female"
                        <?= (($_POST['gender'] ?? '') === 'Female' ? 'checked' : '') ?>> Female</label>
                    <label><input type="radio" name="gender" value="Other"
                        <?= (($_POST['gender'] ?? '') === 'Other'  ? 'checked' : '') ?>> Other</label>
                </fieldset>

                <!-- Date of Birth -->
                <fieldset class="gender-group">
                    <legend>Date of Birth</legend>
                    <div class="dob-group">
                        <input type="text" name="dob_dd"   maxlength="2"
                               value="<?= htmlspecialchars($_POST['dob_dd']   ?? '') ?>" placeholder="dd">
                        <span class="dob-sep">/</span>
                        <input type="text" name="dob_mm"   maxlength="2"
                               value="<?= htmlspecialchars($_POST['dob_mm']   ?? '') ?>" placeholder="mm">
                        <span class="dob-sep">/</span>
                        <input type="text" name="dob_yyyy" maxlength="4"
                               value="<?= htmlspecialchars($_POST['dob_yyyy'] ?? '') ?>" placeholder="yyyy">
                        <span class="dob-hint">(dd/mm/yyyy)</span>
                    </div>
                </fieldset>

                <br>
                <button type="submit" class="btn">Submit</button>
                <button type="reset"  class="btn">Reset</button>

            </form>
        </fieldset>

    </div>

    <!-- FOOTER -->
    <div class="footer">Copyright &copy; 2017</div>

</div>
</body>
</html>
