<?php

// require_once '../vendor/autoload.php';
require_once 'header.php';
require_once 'main.php';

$relUrl = '/phpSiteNews';

?>
<br />
<div class="container">
  <form method="POST" style="width:700px;margin-left:auto;margin-right:auto">
      <div class="form-group">
          Titre :<input type="text" class="form-control" name="titre" value="<?php echo $inputTitre; ?>" />
          <?php if (isset($emptyTitre)) { echo $emptyTitre; } ?>
      </div>
      <div class="form-group">
          <label for="contenu">Contenu :</label>
          <textarea id="contenu" class="form-control" name="contenu" rows="5" cols="33"><?php echo $inputContenu; ?></textarea>
          <?php if (isset($emptyContenu)) { echo $emptyContenu; } ?>
          <br />
      </div>
      <input type="submit" class="btn btn-success" name="vider" value="vider" />
      <input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter" />
  </form>
  <br /><br />
  <h5>Liste de vos tittres de news:
  <table class="table">
  <tr>
    <th>Titre</th>
    <th>Date de publication</th>
  </tr>
    <?php foreach ($newsUser as $key => $value) {
      $dateAjout = $newsUser[$key]->{'dateAjout'};
      $newDateAjout = date('d/m/Y', strtotime($dateAjout));
      $dateModif = $newsUser[$key]->{'dateModif'};
      $newDateModif = date('d/m/y H:i', strtotime($dateModif));
      ?>
    <tr>
        <td><?php echo $newsUser[$key]->{'titre'}; ?></td>
        <td><?php echo $newDateAjout; ?></td>
    </tr>
    <?php
    }
    ?>
  </table>
</div>
<?php


require_once 'footer.php';

?>
