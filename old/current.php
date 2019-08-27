<?php

require_once 'header.php';
require_once 'pdo.php';



if (isset($_SERVER['REQUEST_URI']) == '/tpnews/home/profil.php') {

  $res = $loginCookie->get_account($db);
  // $loginCookie->enabled_account($account_id, $db);
  // var_dump($res);

  $account_enabled = ($res['account_enabled'] == '1') ? 'actif' : 'inactif';
  $account_name = $res['account_name'];
  $account_email = $res['account_email'];
  $account_password = $res['account_email'];
  $confirmed_at = $res['confirmed_at'];
  $confirmation_token = $res['confirmation_token'];

}

if (isset($_SERVER['REQUEST_URI']) == '/tpnews/home/edit.php' && isset($_SERVER['QUERY_STRING']) == 'mdp=edit' && isset($_POST['usermdp'])) {
  $usermdmp = $_POST['usermdp'];


  // $usermdp = $loginCookie->newPassword($email, $newPassword);
}


?>