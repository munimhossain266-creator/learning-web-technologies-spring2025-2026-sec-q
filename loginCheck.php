<?php
    session_start();
    if(isset($_REQUEST['submit'])){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        if($username == "" || $password == ""){
            echo "null username or password!";
        } else {
            // Check if the user exists in our session associative array
            if(isset($_SESSION['users'][$username])){
                $user = $_SESSION['users'][$username];

                // Verify password
                if($user['password'] == $password){
                    $_SESSION['status'] = true;
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on role stored in the array
                    if($user['role'] == "admin"){
                        header('location: adminHome.php');
                    } else {
                        header('location: userHome.php');
                    }
                } else {
                    echo "invalid password!";
                }
            } else {
                echo "user not found! please register.";
            }
        }
    } else {
        header('location: login.html');
    }
?>