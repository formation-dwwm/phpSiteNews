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
        <p>Veuillez remplir pour recevoir un émail !</p>
        <div class="form-group" >
          <label>Email</label>
          <input type="text" name="email" class="form-control" />
          <?php if (isset($echecEmail)) { echo $echecEmail; } ?>
          <?php if (isset($emailExist)) { echo $emailExist; } ?>
          <span class="help-block"></span>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Générer un nouveau mot de passe" />
        </div>
      </form>
    </div>
  </body>
</html>