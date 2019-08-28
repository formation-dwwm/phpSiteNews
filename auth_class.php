<?php

trait StatefulUser {

   /* Authentification par cookie */
  public function cookie_login()
  {
    try {
      if (array_key_exists(self::cookie_name, $_COOKIE)) {
      /* Vérification de la longueur de cookie */
        if (mb_strlen($_COOKIE[self::cookie_name]) < 1) {
          return true;
        }

        $auth_sql = 'SELECT *, UNIX_TIMESTAMP(session_start) AS session_start_ts FROM sessions, accounts WHERE (session_cookie = ?) AND (session_account_id = account_id) AND (account_enabled = 1) AND ((account_expiry > NOW()) OR (account_expiry < ?))';
        $cookie_md5 = md5($_COOKIE[self::cookie_name]);
        $auth_st = $this->db->prepare($auth_sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $auth_st->execute(array($cookie_md5, '2000-01-01'));

        if ($res = $auth_st->fetch(PDO::FETCH_ASSOC)) {
        /* Log in successful */
          $this->account_id = $res['account_id'];
          $this->account_name = $res['account_name'];
          $this->is_authenticated = true;
          $this->expiry_date = $res['account_expiry'];
          $this->session_id = $res['session_id'];
          $this->session_start_time = intval($res['session_start_ts'], 10);
        }
      }
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }
    /* Si aucune exception ne se produit, retourne true */ 
    return true;
  }

  /* Envoie un nouveau cookie d'authentification au navigateur du client et enregistre le hachage de cookie dans la base de données */
  private function create_session() {
    try	{
      /* Créer un nouveau cookie */
      $cookie = bin2hex(random_bytes(16));

      /* Enregistre le hash md5 du nouveau cookie dans la base de données */
      $sql = 'INSERT INTO sessions (session_cookie, session_account_id, session_start) VALUES (?, ?, NOW())';
      $st = $this->db->prepare($sql);
      $st->execute(array(md5($cookie), $this->account_id));

      /* Lit l'ID de session de la nouvelle session de cookie et le stocke dans le paramètre de classe */
      $this->session_id = $this->db->lastInsertId();
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }

    /* Enfin, nous envoyons le cookie à l'utilisateur et nous l'enregistrons dans le fichier $_COOKIE PHP superglobal. */
    setcookie(self::cookie_name, $cookie, time() + self::session_time, '/');
    $_COOKIE[self::cookie_name] = $cookie;

    /* Si aucune exception ne se produit, retourne true */ 
    return true;
  }
}

/* Classe de session d'authentification. */
class User {
   use StatefulUser;

  /* Nom du cookie utilisé pour l'authentification par cookie */
  const cookie_name = 'auth_cookie';

  /* Durée de la session de cookie en secondes (après cela, l'utilisateur doit s'authentifier à nouveau avec un nom d'utilisateur et un mot de passe) */
  const session_time = 604800; // 7 days
  const server_name = "my_server";
  const jwt_key = "MyHighlyUnsecureJwtKey";
  const jwt_algo = "HS512";

  /* Identifiant de compte (extrait de la colonne account_id de la table des comptes) */
  private $account_id;

  /* Nom d'utilisateur du compte */
  private $account_name;

  /* Valeur booléenne définie sur true si l'authentification a réussi */
  private $is_authenticated;

  /* Date d'expiration optionnelle du compte */
  private $expiry_date;

  /* Cookie session ID (colonne session_id de la table des sessions) */
  private $session_id;

  /* Horodatage de la dernière connexion (stocké au format "Horodatage Unix") */
  private $session_start_time;

  /* Objet PDO à utiliser pour les opérations de base de données */
  private $db;

  /* Jeton de confirmation par email */
  private $token;

  /* Jeton de confirmation */
  public $confirmation_token;

  /* url de confirmation de id et Jeton - Mode developpement */
  private $urlToken;

  /* GETTERS PUBLICS */
  public function isAuth() {
    return $this->is_authenticated;
  }

  public function getId() {
    return $this->account_id;
  }

  public function getName() {
    return $this->account_name;
  }

  public function getToken() {
    return $this->token;
  }

  /* Mode developpement */
  public function getUrlToken() {
    return $this->urlToken;
  }
  

  /* FONCTIONS STATIQUES */

  /* FONCTIONS PUBLIQUES */

  /* Constructeur; il prend l'objet $db en argument, passé par référence */
  public function __construct(&$db) {
    $this->account_id = NULL;
    $this->account_name = NULL;
    $this->is_authenticated = false;
    $this->expiry_date = NULL;
    $this->session_id = NULL;
    $this->session_start_time = NULL;
    $this->confirmation_token = false;
    $this->db = $db;
    // $this->urlToken = 'http://localhost:8080';
  }

  /* Traitement de email valide ou unique */
  public function emailExist($email, &$db) {
    try {

      $sql = 'SELECT COUNT(*) FROM accounts WHERE account_email=:email;';
      $st = $db->prepare($sql);
      $st->execute(array(
        ':email' => $email
      ));
      $rows = $st->fetch(PDO::FETCH_ASSOC);

      // si 1 alors email existant retourne true
      if ($rows['COUNT(*)'] > 0) {
        $this->isExist = true;
      } else {
        $this->isExist = false;
      }

    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
    return ['exist' => $this->isExist];
  }

  /* Traitement de username unique */
  public function userExist($username, &$db) {
    try {

      $sql = 'SELECT COUNT(*) FROM accounts WHERE account_name=:username;';
      $st = $db->prepare($sql);
      $st->execute(array(
        ':username' => $username
      ));
      $rows = $st->fetch(PDO::FETCH_ASSOC);

      // si 1 alors utilisateur existant retourne true
      if ($rows['COUNT(*)'] > 0) {
        $this->isUser = true;
      } else {
        $this->isUser = false;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
    return ['user' => $this->isUser];
  }

  /* Ajoute un nouveau compte */
  public function add_account($username, $password, $email, &$db) {

    /* Mot de passe hash */
    $hash = password_hash($password, PASSWORD_DEFAULT);

    /* Jeton Aléatoire */
    function str_random($length){
      $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
      return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }
    $token = str_random(60);

    try {
      /* Ajouter le nouveau compte sur la base de données (c'est une bonne idée de vérifier d'abord si le nom d'utilisateur existe déjà) */
      $sql = 'INSERT INTO accounts (account_name, account_password, account_enabled, account_expiry, account_email, account_token) VALUES (?, ?, ?, ?, ?, ?)';
      $st = $db->prepare($sql);
      $st->execute(array($username, $hash, '1', '1999-01-01', $email, $token));

      $hostUrlSite = 'http://127.0.0.1:8080/phpsitenews';

      // $urlToken = "n\nhttp://127.0.0.1:8080/phpsitenews/confirm_token.php?id=$user_id&token=$token";
      // $urlToken = 'http://localhost:8080';


      $user_id = $db->lastInsertId();
      mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\n$hostUrlSite/confirm_token.php?id=$user_id&token=$token");

    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }
    /* Si aucune exception ne se produit, retourne true */
    return true;
  }

  /* Supprime un compte */
  public function delete_account($account_id, &$db) {
    /* Remarque: les utilisateurs "admin" sont autorisés à exécuter cette fonction. */
    try {
      /* Fermons toute session ouverte que le compte peut avoir */
      $sql = 'DELETE FROM sessions WHERE (session_account_id = ?)';
      $st = $db->prepare($sql);
      $st->execute(array($account_id));

      /* Supprimons l'enregistrement du compte */
      $sql = 'DELETE FROM accounts WHERE (account_id = ?)';
      $st = $db->prepare($sql);
      $st->execute(array($account_id));
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }
    /* Si aucune exception ne se produit, retourne true */
    return true;
  }

  // /* Désactive un compte */
  // public function enabled_account($account_id, &$db) {
  //   /* Remarque: les utlisateurs possédant un compte sont autorisés à éxécuter cette fonction */
  //   try {

  //     /* Modifier la requête */
  //     $sql = 'UPDATE accounts SET account_enabled=:account_enabled WHERE (account_id=:account_id)';
  //     /* Exécuter la requête */
  //     $st = $db->prepare($sql);
  //     $st->execute(array(
  //       ':account_enabled' => '0',
  //       'account_id' => $account_id
  //     ));

  //   } catch (PDOException $e) {
  //     echo $e->getMessage();
  //     return false;
  //   }
  //   return true;
  // }


  public function get_account($username, &$db) {
    /* Note: working progress */

    try {

      $sql = 'SELECT account_name,account_password,account_enabled,account_email,confirmed_at,confirmation_token FROM accounts WHERE account_name=:username';
      $st = $db->prepare($sql);
      $st->execute([':username' => $username]);
      $res = $st->fetch(PDO::FETCH_ASSOC);

      return $res;

    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
    return true;
  }

  /* Éditer un utilisateur existant; les arguments définis sur NULL ne sont pas modifiés */
  public function edit_account($account_id, &$db, $username = NULL, $password = NULL, $enabled = NULL, $expiry = NULL) {
    /* Note: 
      Chaque argument doit être vérifié et validé avant d'exécuter la requête de mise à jour.
      Vérifier la longueur des chaînes (comme dans les autres fonctions), le bon
      format de la date d'expiration et ainsi de suite.
    */

    /* Tableau de valeurs pour l'instruction PDO */
    $sql_vars = array();

    /* Modifier la requête */
    $sql = 'UPDATE accounts SET ';

    /* Maintenant nous vérifions quels champs doivent être mis à jour */
    if (!is_null($username)) {
      $sql .= 'account_name = ?, ';
      $sql_vars[] = $username;
    }

    if (!is_null($password)) {
      $sql .= 'account_password = ?, ';
      $sql_vars[] = password_hash($password, PASSWORD_DEFAULT);
    }

    if (!is_null($enabled)) {
      $sql .= 'account_enabled = ?, ';
      $sql_vars[] = strval(intval($enabled, 10));
    }

    if (!is_null($expiry)) {
      $sql .= 'account_expiry = ?, ';
      $sql_vars[] = $expiry;
    }

    if (count($sql_vars) == 0) {
      /* Rien à changer */
      return true;
    }

    $sql = mb_substr($sql, 0, -2) . ' WHERE (account_id = ?)';
    $sql_vars[] = $account_id;

    try {
      /* Exécuter la requête */
      $st = $db->prepare($sql);
      $st->execute($sql_vars);
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }
    /* Si aucune exception ne se produit, retourne true */
    return true;
  }

  /* Authentification par nom d'utilisateur et mot de passe */
  public function login($name, $password, $useJWT = false, &$JWT = NULL) {
    try {
      /* D'abord nous recherchons le nom d'utilisateur */
      $sql = 'SELECT * FROM accounts WHERE (account_name = ?) AND (account_enabled = 1) AND ((account_expiry > NOW()) OR (account_expiry < ?))';
      //   $sql = 'SELECT * FROM accounts WHERE (account_name = ?) AND (account_enabled = 1) AND ((account_expiry > NOW()) OR (account_expiry < ?)) AND (confirmation_token = ?)';

      $st = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $st->execute(array($name, '2000-01-01'));
      //   $st->execute(array($name, '2000-01-01', 'true'));
      $res = $st->fetch(PDO::FETCH_ASSOC);

      /* Si le nom d'utilisateur existe et est activé, nous vérifions le mot de passe */
      if (password_verify($password, $res['account_password']) && $res['confirmation_token'] == 'true') {
        /* Log in ok, we retrieve the account data */
        $this->account_id = $res['account_id'];
        $this->account_name = $res['account_name'];
        $this->is_authenticated = true;
        $this->expiry_date = $res['account_expiry'];
        $this->session_start_time = time();
        $this->confirmation_token = $res['confirmation_token'];

        /* Maintenant, nous créons le cookie et l'envoyons au navigateur de l'utilisateur */
        $this->create_session();

        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }
    /* Si aucune exception ne se produit, retourne true */ 
    return true;
  }

  /* Ancien mot de passe remplacé */
  public function newPassword($email, $newPassword) {
    try {
          /* Mot de passe hash */
          $hash = password_hash($newPassword, PASSWORD_DEFAULT);

          $sql = 'UPDATE accounts SET account_password=:account_password WHERE account_email=:account_email;';
          $st = $this->db->prepare($sql);
          $st->execute(array(
            ':account_email' => $email,
            ':account_password' => $hash
          ));

    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
    return true;
  }

  /* Déconnecte l'utilisateur et ferme sa session en cours (et toutes les autres sessions si $close_all_sessions est true) */
  public function logout($close_all_sessions = false) {
    /* D'abord, nous vérifions si un cookie existe */
    if (strlen($_COOKIE[self::cookie_name]) < 1) {
      return true;
    }

    try {
      /* Tout d'abord, nous fermons la session en cours */
      $cookie_md5 = md5($_COOKIE[self::cookie_name]);
      $sql = 'DELETE FROM sessions WHERE (session_cookie = ?) AND (session_account_id = ?)';
      $st = $this->db->prepare($sql);
      $st->execute(array($cookie_md5, $this->account_id));

      /* Avons-nous également besoin de fermer d'autres sessions? */
      if ($close_all_sessions) {
        /* Nous fermons toutes les sessions du compte */
        $sql = 'DELETE FROM sessions WHERE (session_account_id = ?)';
        $st = $this->db->prepare($sql);
        $st->execute(array($this->account_id));
      }
    } catch (PDOException $e) {
      /* Exception (erreur SQL) */
      echo $e->getMessage();
      return false;
    }

    /* Supprime le cookie du navigateur de l'utilisateur */
    setcookie(self::cookie_name, '', 0, '/');
    $_COOKIE[self::cookie_name] = NULL;

    /* Effacer les propriétés liées à l'utilisateur */
    $this->account_id = NULL;
    $this->account_name = NULL;
    $this->is_authenticated = false;
    $this->expiry_date = NULL;
    $this->session_id = NULL;
    $this->session_start_time = NULL;

  /* Si aucune exception ne se produit, retourne true */ 
    return true;
  }
}