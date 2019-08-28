<?php

$relUrl = '/phpsitenews';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.php" type="text/css" />
  </head>
  <body>
<?php

require_once '../auth_cookie.php';
require_once 'main.php';

if(isset($_COOKIE['auth_cookie'])) {
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
      // echo 'Tu est connecté en tant qu\'administrateur';
    }
} else {
    header('location: ' . $relUrl . '/login.php');
}

if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $classAdmin = 'class="nav-link" style="color:red;"';
    $hrefAdmin = 'href="' . $relUrl . '/admin/admin.php"';
} else {
    $classAdmin = 'style="display:none;"';
    $hrefAdmin = '#"';
}

?>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <h3 tabindex="0" >Site de news</h3>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto" role="menu">
        <li class="nav-item <?php echo $activeAccueil; ?>" style="margin-left:30px;">
          <a class="nav-link" href="<?php echo $relUrl; ?>/home/accueil.php" tabindex="1">Accueil</a>
        </li>
        <li class="nav-item <?php echo $activeCreate; ?>">
          <a class="nav-link" href="<?php echo $relUrl; ?>/home/create.php" tabindex="2">Create</a>
        </li>
        <li class="nav-item <?php echo $activeAdmin; ?>">
          <a <?php echo $classAdmin; ?><?php echo $hrefAdmin; ?> tabindex="3">Admin</a>
        </li>
        <li class="nav-item dropdown <?php echo $activeProfil; ?>" role="menu">
          <a class="nav-link dropdown-toggle" href="../accounts.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="4">Compte</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
              <a class="dropdown-item" href="<?php echo $relUrl . '/home/profil.php'; ?>" tabindex="5">Edit Profil</a>
              <form id="delete" method="post" action="../accounts.php">
                <input type="hidden" name="delete" value="delete" />
              </form>
              <!-- <a class="dropdown-item" href="#" onclick="document.getElementById('delete').submit()">Supprimer compte</a> -->
              <div class="dropdown-divider"></div>
              <form id="close" method="post" action="../accounts.php">
                <input type="hidden" name="close" value="close" />
              </form>
              <a class="dropdown-item" href="#" onclick="document.getElementById('close').submit()" tabindex="6">Déconnecter</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

