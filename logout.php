<?php
session_start();

// Destroy all session data (logs the user out)
session_unset();
session_destroy();

// Also clear the Remember Me cookie
setcookie('remember_user', '', time() - 3600, '/');

// Redirect to public home
header("Location: index.php");
exit;
