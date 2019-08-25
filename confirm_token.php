<?php

require_once 'auth_cookie.php'; 


$user_id = $_GET['id'];
$token = $_GET['token'];

$req = $db->prepare('SELECT * FROM accounts WHERE account_id = ?');
$req->execute([$user_id]);
$userAuth = $req->fetch();
// var_dump($userAuth);

if($userAuth && $userAuth['account_token'] == $token){
    $db->prepare('UPDATE accounts SET account_token = NULL, confirmed_at = NOW() , confirmation_token = "true" WHERE account_id = ?')->execute([$user_id]);

    $_SESSION['token'] = 'success';
    $_SESSION['newmdp'] = 'start';
    header('location: login.php');
} else {
    $_SESSION['token'] = 'fail';
    $_SESSION['newmdp'] = 'start';
    header('location: login.php');
}

// Se rendre dans phpMyAdmin pour connaitre les infos
// http://127.0.0.1:8080/tpnews/confirm_token.php?id=44&token=PcZKkp8GEqcbAwcZFNhxwKcgw2jjY78V6nZoMAlyzJ18QrQuNvHHCHPxmMgX