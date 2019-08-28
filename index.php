<?php

// Work In Progress
// if($_COOKIE['auth_cookie']) {
//     echo 'Vous êtes connecté';
//     header('Location: home/home.php');
// } else {
// header('Location: login.php');
// }


  // $_SESSION['newmdp'] = 'start';
  // $_SESSION['token'] = 'start';
  // $_SESSION['login'] = 'start';
  // $_SESSION['email'] = 'start';
  // header('Refresh: 0');
  // exit();

// if (!isset($_SERVER['username']) && !isset($_SESSION['password'])) {
//   header('WWW-Authenticate: Basic realm="My Realm"');
//   header('HTTP/1.0 401 Unauthorized');
//   echo 'Partie du site inutilisable';

  header('location: /phpsitenews/login.php');
  exit;
// } else {
//   require_once 'auth_cookie.php';
// }


// session_start();
// echo($loginCookie->getName());

?>