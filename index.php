<?php

// Work In Progress
if($_COOKIE['auth_cookie']) {
    echo 'Vous êtes connecté';
    header('Location: home/home.php');
} else {
    header('Location: login.php');
}

require_once 'auth_cookie.php';


session_start();
echo($loginCookie->getName());

?>