<?php

// A session is required
session_start();

require_once 'auth_cookie.php';
require_once 'env.php';

$host = 'http://127.0.0.1:8080';
$relUrl = '/phpSiteNews';

// $urlToken = 'http://127.0.0.1:8080/phpSiteNews/confirm_token.php?id=44&token=PcZKkp8GEqcbAwcZFNhxwKcgw2jjY78V6nZoMAlyzJ18QrQuNvHHCHPxmMgX';
// $urlNewmdp = 'http://127.0.0.1:8080/phpSiteNews/confirm_mdp.php?mdp=6w6loaoe';

// Conditions d'affichages des variables d'alertes avec les sessions
/* LOGIN cookie*/
if (!isset($_SESSION['login']) && !isset($_SESSION['token']) && !isset($_SESSION['email']) && !isset($_SESSION['newmdp'])){
  $_SESSION['login'] = '';
  $_SESSION['token'] = '';
  $_SESSION['email'] = '';
  $_SESSION['newmdp'] = '';
}
$displayNone = 'style="display:none;"';
/* LOGIN valide */
if (isset($_SESSION['login'])) {
  $loginDelay = ($_SESSION['login'] == 'delay') ? '' : $displayNone;
  $loginSuccess = ($_SESSION['login'] == 'success') ? '' : $displayNone;
  $loginClose = ($_SESSION['login'] == 'close') ? '' : $displayNone;
  $loginDelete = ( $_SESSION['login'] == 'delete') ? '' : $displayNone;
  $loginEchec = ($_SESSION['login'] == 'echec') ? '' : $displayNone;
} else {
  $_SESSION['login'] = 'start';
}
/* TOKEN transitoire */
if (isset($_SESSION['token'])) {
  $tokenDelay = ($_SESSION['token'] == 'delay') ? '' : $displayNone;
  $tokenSuccess = ($_SESSION['token'] == 'success') ? '' : $displayNone;
  $tokenFail = ($_SESSION['token'] == 'fail') ? '' : $displayNone;
} else {
  $_SESSION['token'] = 'start';
}
/* EMAIL unique et valide*/
if (isset($_SESSION['email'])) {
  $emailFail = ($_SESSION['email'] = 'fail') ? '' : $displayNone;
  $emailSuccess = ($_SESSION['email'] = 'success') ? '' : $displayNone;
} else {
  $_SESSION['email'] = 'start';
}
/* PASSWORD oublié */
if (isset($_SESSION['newmdp'])) {
  $mdpDelay = ($_SESSION['newmdp'] == 'delay') ? '' : $displayNone;
  $mdpSuccess = ($_SESSION['newmdp'] == 'success') ? '' : $displayNone;
  $mdpFail = ($_SESSION['newmdp'] == 'fail') ? '' : $displayNone;
} else {
    $_SESSION['newmdp'] = 'start';
}

/* VISIBILITE recaptcha */
if (isset($_SERVER['REQUEST_URI']) === $relUrl . '/register.php') {
  $recaptcha = ($_SERVER['HTTP_REFERER'] === $host . $relUrl . '/register.php') ?  '' : $displayNone;
}

// Controle de la validité de EMAIL unique - TOKEN de confirmation url
function emailCheck($email, $key, $bool) {
  // Supprimer tous les caractères illégaux de l'email
  if (empty($email)) {
    $echecEmail = '<span style="color:red;">Veuillez remplir votre email</span>';
    $emailExist = '';
  } else if (!empty($email)) {
      $echecEmail = '<span style="color:red;">Email invalide</span>';
      $emailExist = '';
    if ($bool === true && $key == 'exist') {
        $echecEmail = '';
        $emailExist = '<span style="color:blue;">Votre email existe déjà !</span>';
        // $emailExist = '';              // Possibilité d'un echec silencieux avec fail sur confirm par url
    } else if ($bool === false && $key == 'valid') {
      $echecEmail = '';
      $emailExist = '<span style="color:blue;">Votre email n\'existe pas !</span>';
      // $emailExist = '';              // Possibilité d'un echec silencieux avec fail sur confirm par url
    } else {
      $echecEmail = '';
      $emailExist = '';
    }
  }
  $arr = array(
    'echecEmail' => $echecEmail,
    'emailExist' => $emailExist
  );
  return $arr;
}

// Controle de la validité de username - Mode Developpement
function usernameCheck($name, $key, $bool) {
  if ($key == 'user') {
    if (empty($name)) {
      $echecName = '<span style="color:red;">Veuillez remplir votre nom d\'utilisateur</span>';
      $userExist = '';
    } else if (!empty($name)) {
      if (strlen($name) < 5 || strlen($name) > 255) {
          $echecName = '<span style="color:red;">5 caractères requis min et 255 max.</span>';
          $userExist = '';
      } else {
        $echecName = '';
        if ($bool === true) {
          $userExist = '<span style="color:blue;">l\'utilisateur existe déjà !</span>';
        } else {
          $userExist = '';
        }
      }
    }
  } else if ($key == '') {
    $userExist = '';
    if (empty($name)) {
      $echecName = '<span style="color:red;">Veuillez remplir votre nom d\'utilisateur</span>';
    } else if (!empty($name)) {
      if (strlen($name) < 5 || strlen($name) > 255) {
          $echecName = '<span style="color:red;">5 caractères requis min et 255 max.</span>';
      } else {
        $echecName = '';
      }
    }
  }
  $arr = array(
    'echecName' => $echecName,
    'userExist' => $userExist
  );
  return $arr;
}

// Contrôle de la validité de PASSWORD
function passwordCheck($password) {
  if (empty($password)) {
    $echecPass = '<span style="color:red;">Veuillez remplir votre mot de passe</span>';
  } else if (!empty($password)) {
    if (strlen($password) < 5 || strlen($password) > 255) {
      $echecPass = '<span style="color:red;">5 caractères requis min et 255 max.</span>';
    } else {
      $echecPass = '';
    }
  }
  $arr = array('echecPass' => $echecPass);
  return $arr;
}

/*---------------------------------------------EMAIL NEW PASSWORD---------------------------------------------------*/
// Traitement de rentrer de l'email - Génération du hesh et envoi demande url
if ($_SERVER['REQUEST_URI'] === $relUrl . '/mdpforget.php' && isset($_POST['email'])) {
  $email = $_POST['email'];
  $_SESSION['emailValue'] = $email;

  // Génération d'une chaine aléatoire
  function chaine_aleatoire($nb, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789') {
      $lettres = strlen($chaine) - 1;
      $generation = '';
      for($i = 0; $i < $nb; ++$i)
      {
          $position = mt_rand(0, $lettres);
          $cararacter = $chaine[$position];
          $res .= $cararacter;
      }
      return $res;
  }

  // Controle de la validité de EMAIL unique - TOKEN de confirmation url
  if (empty($email)) {
    $resEmail = emailCheck($email, '', '');
    $resValidBool = false;
  } else {
    $resValid = $loginCookie->emailExist($email, $db);
    $resValidBool = $resValid['exist'];
    $resEmail = emailCheck($email, 'valid', $resValidBool );
  }
  $echecEmail = $resEmail['echecEmail'];
  $emailExist = $resEmail['emailExist'];

  // $newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
  // $checkEmail = filter_var($newEmail, FILTER_VALIDATE_EMAIL);

  if ($resValidBool == true) {
    // $mdpTempo = chaine_aleatoire(8);
    $mdpTempo = '6w6loaoe';      // mdp fixe pour le développement
    $_SESSION['mdp'] = $mdpTempo;
    // echo $_SESSION['mdp'];
    $_SESSION['newmdp'] = 'delay';
    $_SESSION['token'] = 'start';
    $_SESSION['login'] = 'start';
    header('location: login.php');
    exit();
  }
}

/*---------------------------------------------NEW PASSWORD---------------------------------------------------*/
// Traitement d'entrer du nouveau PASSWORD après confirmation url
if ($_SERVER['REQUEST_URI'] === $relUrl . '/mdpnew.php' && isset($_POST['newPassword']) && isset($_POST['repeatPassword'])) {
  // echo $_SESSION['emailValue'];
  if (isset($_SESSION['emailValue']) && isset($_SESSION['mdp']) == '6w6loaoe') {
    $newPassword = $_POST['newPassword'];
    $repeatPassword = $_POST['repeatPassword'];

    // Controle de la validité de PASSWORD
    $resNewPass = passwordCheck($newPassword);
    $echecNewPass1 = $resNewPass['echecPass'];
    $resRepeatPass = passwordCheck($repeatPassword);
    $echecNewPass2 = $resRepeatPass['echecPass'];
    $echecBetweenPass = '';

    // Controle si PASSWORD && repeat sont identiques - Mode Developpement
    if (empty($echecNewPass2) &&  $newPassword != $repeatPassword) {
      $echecNewPass2 = '';
      $echecBetweenPass = '<span style="color:red;">Vos mots de passes ne correspondent pas !</span>';
    }
    if ($echecNewPass1 == '' && $echecNewPass2 == '' && $echecBetweenPass == '') {
      // Récupération émail avec UPDATE PASSWORD
      if ($loginCookie->newPassword($email, $newPassword)) {
        unset($_SESSION['emailValue']);
        $_SESSION['newmdp'] = 'success';
        $_SESSION['login'] = 'start';
        header('location: login.php');
        exit();
      }
    } else if (isset($_POST['echecnewmdp'])) {
      $_SESSION['newmdp'] = 'fail';
      header('location: login.php');
      exit();
    }
  } else {
    $_SESSION['newmdp'] = 'fail';
    header('location: login.php');
    exit();
  }
}

  if (isset($_SESSION['token']) == 'delay') {
    $urlToken = $loginCookie->getUrlToken();
  }

/*---------------------------------------------LOGIN---------------------------------------------------*/
// Traitement de récupération LOGIN et PASSWORD
if ($_SERVER['REQUEST_URI'] === $relUrl . '/login.php' && isset($_POST['username']) && isset($_POST['password'])) {
  $name = $_POST['username'];
  $password = $_POST['password'];
  $_SESSION['newmdp'] = 'start';
  $_SESSION['token'] = 'start';
  $_SESSION['login'] = 'start';
  $_SESSION['email'] = 'start';

  $newPassordUser = $host . $relUrl . '/confirm_mdp.php?mdp=6w6loaoe';

  // Controle de la validité de username - Mode Developpement
  $resUsername = usernameCheck($name, '', '');
  $echecName = $resUsername['echecName'];

  // Controle de la validité de password - Mode Developpement
  $resPass = passwordCheck($password);
  $echecPass = $resPass['echecPass'];

  // Réalisation du role de l'administrateur - HOME PAGE connect SUCCESS
  if ($echecName == '' && $echecPass == '') {
    
    $loginCookie->cookie_login();
    
    if ($loginCookie->login($name, $password)) {
      $_SESSION['username'] = $name;
      $_SESSION['password'] = $password;
      // Reléve l'utilisateur "admin" dans le fichier roles.json 
      $rolesAdmin = json_decode(file_get_contents('roles.json'), true);
      $nameRole = $rolesAdmin['Authentification'][0]['username'];
      $passwordRole = $rolesAdmin['Authentification'][0]['password'];

      if ($name == $nameRole && $password == $passwordRole) {
        // Session qui donne le role d'administrateur à un utilisateur
        $_SESSION['role'] = 'admin';
      } else {
          unset($_SESSION['role']);
      };
      header('location: ' . $relUrl . '/home/accueil.php');
      exit();
    } else {
      $_SESSION['login'] = 'echec';
    }
  }
}

/*---------------------------------------------REGISTER---------------------------------------------------*/
// Traitement d'entrer Unique de EMAIL, USERNAME et PASSWORD
if ($_SERVER['REQUEST_URI'] === $relUrl . '/register.php' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['cgu_accept'])) {
  $email = $_POST['email'];
  $name = $_POST['username'];
  $password = $_POST['password'];
  $cgu_accept = $_POST['cgu_accept'];
  $_SESSION['newmdp'] = 'start';

  // Controle de la validité de EMAIL unique - TOKEN de confirmation url
  if (empty($email)) {
    $resEmail = emailCheck($email, '', '');
    $resExistBool = true;
  } else {
    $resExist = $loginCookie->emailExist($email, $db);
    $resExistBool = $resExist['exist'];
    $resEmail = emailCheck($email, 'exist', $resExistBool );
  }
  $echecEmail = $resEmail['echecEmail'];
  $emailExist = $resEmail['emailExist'];

  // Controle de la validité de l'utilisateur unique - TOKEN de confirmation url
  $resUser = $loginCookie->userExist($name, $db);
  $resUserExist = $resUser['user'];
  $resUsername = usernameCheck($name, 'user', $resUserExist);
  $echecName = $resUsername['echecName'];
  $userExist = $resUsername['userExist'];

  // Controle de la validité de PASSWORD - Mode Developpement
  $resPass = passwordCheck($password);
  $echecPass = $resPass['echecPass'];

  // Controle de la validité de la mentions légale
  if ($cgu_accept == '0') {
    $echecCgu = '<span style="color:red;display:inline-block;">Vous n\'êtes pas d\'accord avec les conditions de service</span>';
  } else {
    $echecCgu = '';
  }

  if ($resExistBool == false && $resUserExist == false && $echecPass == '' && $echecCgu == '') {
    // TOKEN de confirmation url
    if ($loginCookie->add_account($name, $password, $email, $db)) {
      $_SESSION['token'] = 'delay';
      $_SESSION['login'] = 'start';
      header('location: login.php');
      exit();
    }
  }
}

/*---------------------------------------------CLOSE---------------------------------------------------*/
// Déconnection par l'utilisateur - Page de logout
if(isset($_POST['close'])) {
  $_SESSION['login'] = 'close';
  $loginCookie->logout($close_all_sessions = true);
  unset($_SESSION['username']);
  unset($_SESSION['password']);
  header('location: login.php');
  exit();
}

/*---------------------------------------------DELETE---------------------------------------------------*/
// Suppression du compte par l'utilisateur - Page de logout
if(isset($_POST['delete'])) {
  $_SESSION['login'] = 'delete';
  $account_id = $loginCookie->getId();
  $loginCookie->delete_account($account_id, $db);
  $loginCookie->logout($close_all_sessions = true);
  $_SESSION['login'] = 'delete';
  unset($_SESSION['username']);
  unset($_SESSION['password']);
  header('location: login.php');
  exit();
}

// Capcha GOOGLE - INACTIF pour le développement
if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
  $secretkey = Env::get_secret();
  $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response='.$_POST['g-recaptcha-response']);
  $responseData = json_decode($verifyResponse);
  if($responseData->success) {
      $succMsg = 'Your contact request have submitted successfully.';
  }
  else {
      $errMsg = 'Robot verification failed, please try again.';
  }
}