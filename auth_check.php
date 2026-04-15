<?php
/**
 * auth_check.php
 * Include this at the top of every protected page.
 * If the user is not logged in, they are redirected to login.php.
 */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
