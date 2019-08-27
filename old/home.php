<?php

// require_once '../vendor/autoload.php';
require_once 'header.php';
require_once 'main.php';


?>
<br />
<div class="alert alert-success" <?php echo $loginSuccess; ?> role="alert">
  Votre login est validé. Vous êtes connecté.
</div>
<h1>Work in Progress</h1>
<form method="post" action="accueil.php">
    <input class="btn btn-success" type="submit" value="entrer" />
</form>
<?php


require_once 'footer.php';

?>

