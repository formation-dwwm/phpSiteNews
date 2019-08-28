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
    <h2>S'inscrire</h2>
    <p>Veuillez remplir ce formulaire pour créer un compte.</p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control" />
            <?php if (isset($echecEmail)) { echo $echecEmail; } ?>
            <?php if (isset($emailExist)) { echo $emailExist; } ?>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" />
            <?php if (isset($echecName)) { echo $echecName; } ?>
            <?php if (isset($userExist)) { echo $userExist; } ?>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" />
            <?php if (isset($echecPass)) { echo $echecPass; }?>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Envoyer" />
        </div>
        <div class="form-group">
        <input type="hidden" name="cgu_accept" value="0" />
        <input type="checkbox" name="cgu_accept" id="cgu" /><label for="cgu" style="display:inline;margin-left:5px;">J'accepte les <a href="<?php if(isset($hostUrlSite)) { echo $hostUrlSite; } ?>/CGU.pdf">Conditions Générales d'Utilisation</a></label>
          <?php if (isset($echecCgu)) { echo $echecCgu; } ?>
        </div>
        <p>Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a>.</p>
      </form>
    </div>
  </body>
</html>