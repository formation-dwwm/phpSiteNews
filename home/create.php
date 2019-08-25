<?php

// require_once '../vendor/autoload.php';
require_once 'header.php';
require_once 'main.php';


?>
<br />
<div class="container">
  <form method="POST" style="width:700px;margin-left:auto;margin-right:auto">
      <div class="form-group">
          Auteur :<input type="text" class="form-control" name="auteur" value="<?php echo $inputAuteur; ?>" />
      </div>
      <div class="form-group">
          Titre :<input type="text" class="form-control" name="titre" value="<?php echo $inputTitre; ?>" />
      </div>
      <div class="form-group">
          <label for="contenu">Contenu :</label>
          <textarea id="contenu" class="form-control" name="contenu" rows="5" cols="33"><?php echo $inputContenu; ?></textarea>
      </div>
      <input type="submit" class="btn btn-success" name="vider" value="vider" />
      <input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter" />
  </form>
</div>
<?php


require_once 'footer.php';

?>
