<?php

require_once 'auth_cookie.php'; 


$user_mdp = $_GET['mdp'];

if ($_SESSION['mdp'] == $user_mdp) {
    $_SESSION['newmdp'] == 'success';
    $_SESSION['token'] = 'start';
    $_SESSION['urlNewmdp'] = 'http://127.0.0.1:8080/tpnews/confirm_mdp.php?mdp=6w6loaoe';
    header('location: mdpnew.php');
    exit();
} else {
    $_SESSION['newmdp'] == 'fail';
    header('location: login.php');
    exit();
}

// mdp=6w6loaoe fixe pour url provisoire de confirmation
// http://127.0.0.1:8080/tpnews/confirm_mdp.php?mdp=6w6loaoe

