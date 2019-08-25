<?php

require_once 'auth_cookie.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>S'identifiez</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
      body{ font: 14px sans-serif; }
      .wrapper{ width: 350px; padding: 20px; }
    </style>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
  </head>
  <body>
  
    <div class="alert alert-success" <?php if (isset($tokenDelay)) { echo $tokenDelay; } ?> role="alert">
      Un émail d'envoi vous a été transmis pour votre confirmation d'inscription.
    </div>
    <div class="alert alert-danger" <?php if (isset($tokenFail)) { echo $tokenFail; } ?> role="alert">
      La validation de votre url avec votre token à échouer !
    </div>
    <div class="alert alert-warning" <?php if (isset($tokenSuccess)) { echo $tokenSuccess; } ?> role="alert">
      La confirmation de votre émail est prise en compte.
    </div>
    <div class="alert alert-success" <?php if (isset($mdpDelay)) { echo $mdpDelay; } ?> role="alert">
      Un émail d'envoi avec un lien vous a été transmis pour entrer votre nouveau mot de passe.
    </div>
    <div class="alert alert-danger" <?php if (isset($mdpFail)) { echo $mdpFail; } ?> role="alert">
      La prise en compte de votre mot de passe à échouer ! Activer votre lien reçu par email !
    </div>
    <div class="alert alert-danger" <?php if (isset($loginEchec)) { echo $loginEchec; } ?> role="alert">
    La prise en compte de votre mot de passe à échouer !
    </div>
    <div class="alert alert-warning" <?php if (isset($mdpSuccess)) { echo $mdpSuccess; } ?> role="alert">
      La confirmation de votre nouveau de passe est prise en compte.
    </div>
    <div class="alert alert-warning" <?php if (isset($loginClose)) { echo $loginClose; } ?> role="alert">
        Vous avez été déconnecté!
    </div>
    <div class="alert alert-danger" <?php if (isset($loginDelete)) { echo $loginDelete; } ?> role="alert">
        Nous vous confirmons que votre compte est supprimé. A bientôt !
    </div>
    <div class="wrapper" style="margin-left:auto; margin-right:auto;">
      <h2>Login</h2>
      <p>S'il vous plaît remplir vos informations d'identification pour vous connecter</p>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
          <label>Nom d'utilisateur</label>
          <input type="text" name="username" class="form-control" />
          <?php if (isset($echecName)) { echo $echecName; } ?>
          <span class="help-block"></span>
        </div>
        <div class="form-group">
          <label>Mot de passe</label>
          <input type="password" name="password" class="form-control" />
          <?php if (isset($echecPass)) { echo $echecPass; } ?>
          <span class="help-block"></span>
          <a href="mdpforget.php" style="font-size:12px;">Mot de Passe Oublié ?</a>
        </div>
        <div class="form-group">
        <input type="hidden" name="cgu_accept" value="0" />
        <input type="checkbox" name="cgu_accept" id="cgu" /><label for="cgu" style="display:inline;margin-left:5px;">J'accepte les <a href="http://127.0.0.1:8080/tpnews/CGU.pdf">Conditions Générales d'Utilisation</a></label>
          <?php if (isset($echecCgu)) { echo $echecCgu; } ?>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="S'identifier" />
        </div>
        <p>Avez-vous un compte? <a href="register.php">Signer ici</a>.</p>
      </form>
      <div class="g-recaptcha" <?php if(isset($recaptcha)) { echo $recaptcha; } ?> data-sitekey="6LfmR68UAAAAADghOs1ZmgQe7PZmlcASj5UKOv_X">
    </div>
  </body>
</html>