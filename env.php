<?php 

class Env {

  /* Objet PDO à utiliser pour les opérations de base de données phpAuth */
  private static $db;

  /* Objet PDO à utiliser pour les opérations de base de données tpNews */
  private static $pdo;

  /* secret du recaptcha google */
  private static $secretkey;

  /* Objet PDO à utiliser pour les opérations de base de données phpAuth */
  private $connection_db;

  /* Objet PDO à utiliser pour les opérations de base de données tpNews */
  private $connection_pdo;

  /* secret du recaptcha google */
  private $data_secretkey;

  const DB_USERNAME = 'Your_USERNAME';
  const DB_PASSWORD ='Your_PASSWORD';
  const SECRET_CAPTCHA = 'Your_SECRET8CAPTCHA_GOOGLE';
  const KEY_CAPTCHA = 'Your_KEY_CAPTCHA_GOOGLE';

  /* Constructeur */
  private function __construct() {
      $this->connection_db = new PDO('mysql:host=localhost:3305;dbname=phpAuth', self::DB_USERNAME, self::DB_PASSWORD);
      $this->connection_pdo = new PDO('mysql:host=localhost:3305;dbname=tpnews', self::DB_USERNAME, self::DB_PASSWORD);
      $this->data_secretkey = self::SECRET_CAPTCHA;
      $this->data_sitekey = self::KEY_CAPTCHA;
  }

  /* Permet la connection à la base de données phpAuth */
  public static function get_db() {
      if (self::$db == null) {
          self::$db = new Env();
      }
      return self::$db->connection_db;
  }

  /* Permet la connection à la base de données tpNews */
  public static function get_pdo() {
    if (self::$pdo == null) {
        self::$pdo = new Env();
    }
    return self::$pdo->connection_pdo;
  }

  /* Permet l'affichage de recaptcha google */
  public static function get_secret() {
    if (self::$secretkey == null) {
        self::$secretkey = new Env();
    }
    return self::$secretkey->data_secretkey;
  }
}
