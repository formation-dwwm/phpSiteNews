<?php

require_once 'auth_class.php'; 
require_once 'db.php';


$loginCookie = new User($db);
$loginCookie->cookie_login();


require_once 'accounts.php';


?>