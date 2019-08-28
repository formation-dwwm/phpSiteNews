<?php

require_once 'header.php';
require_once 'main.php';

$relUrl = '/phpsitenews';

?>
<h5 style="padding:10px;margin-left:0;">Details de nouvelle :</h5>
<div style="margin-left:auto;margin-right:auto;">
  <div class="media" style="width:40rem;margin-left:auto;margin-right:auto;">
    <!-- <img src="..." class="align-self-start mr-3" alt="..."> -->
    <div class="media-body" style="border:1px solid #ddd8d8;border-radius:10px;padding:15px 25px;">
      <h5 class="mt-0"><?php echo $texteTitre; ?></h5>
      <p style="text-align:justify;"><?php echo $texteContenu; ?></a>
      <br /><br />
      <span><strong>Auteur : <?php echo $texteAuteur;?></strong></span>
    </div>
    <br />
</div>
<br />
<a href="accueil.php" class="btn btn-success" style="margin-left:auto;margin-right:auto;">Retour</a>
<?php

require_once 'footer.php';

?>