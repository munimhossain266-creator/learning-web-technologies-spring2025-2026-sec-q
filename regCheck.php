<?php
    session_start();
    if(isset($_REQUEST['submit'])){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $role = $_REQUEST['role'];

        if($username == "" || $password == "" || $role == ""){
            echo "Please fill all fields!";
        } else {
            if(!isset($_SESSION['users'])){
                $_SESSION['users'] = [];
            }

            // Initialize default products if they don't exist
            if(!isset($_SESSION['products'])){
                $_SESSION['products'] = [
                    ['id' => 1, 'name' => 'Product 1', 'price' => 100, 'qty' => 10],
                    ['id' => 2, 'name' => 'Product 2', 'price' => 200, 'qty' => 5]
                ];
            }

            $_SESSION['users'][$username] = [
                'username' => $username,
                'password' => $password,
                'role'     => $role
            ];

            header('location: login.html');
        }
    } else {
        header('location: register.html');
    }
?>