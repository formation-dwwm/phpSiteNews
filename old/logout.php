<?php
// Work In Progress
require_once 'auth_cookie.php';


?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Bienvenue</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <style type="text/css">
          body{ font: 14px sans-serif; text-align: center; }
      </style>
  </head>
  <body>
    <div class="alert alert-warning" <?php if (isset($loginClose)) { echo $loginClose; } ?> role="alert">
        Vous avez été déconnecté!
    </div>
    <div class="alert alert-danger" <?php if (isset($loginDelete)) { echo $loginDelete; } ?> role="alert">
        Nous vous confirmons que votre compte est supprimé. A bientôt !
    </div>
    <div class="page-header" <?php if (isset($loginDelete)) { echo $loginDelete; } ?>>
        <h1>Votre compte à bien été supprimé.</h1>
    </div>
    <br />
    <div class="form-group">
      <form action="login.php" method="post">
        <input type="submit" class="btn btn-danger" name="logout" value="Revenir à l'accueil" />
      </form>
    </div>
  </body>
</html>
