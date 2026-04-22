<?php
    session_start();
    
    // Clear all session variables
    session_unset();
    
    // Destroy the session
    session_destroy();

    // Redirect back to login page
    header('location: login.html');
?>