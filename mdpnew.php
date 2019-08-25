<?php

require_once 'auth_cookie.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Sign Up</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <style type="text/css">
          body{ font: 14px sans-serif; }
          .wrapper{ width: 350px; padding: 20px; }
      </style>
  </head>
  <body>
    <div class="wrapper" style="margin-left:auto; margin-right:auto;">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <p>Veuillez donner votre nouveau mot de passe !</p>
        <div class="form-group">
          <label>Nouveau Mot de passe</label>
          <input type="password" name="newPassword" class="form-control" />
          <?php if (isset($echecNewPass1)) { echo $echecNewPass1; } ?>
          <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label>Répéter votre Mot de passe</label>
            <input type="password" name="repeatPassword" class="form-control" />
            <?php if (isset($echecNewPass2)) { echo $echecNewPass2; } ?>
            <?php if (isset($echecBetweenPass)) { echo $echecBetweenPass; } ?>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Valider" />
            <input type="submit" class="btn btn-info" name="echecnewmdp" style="<?php if (empty($echecNewPass1) || empty($echecNewPass2)) { echo 'display:none;'; } ?>" value="Revenir à l'accueil" />
        </div>
      </form>
      
    </div>
  </body>
</html>