<?php

require_once 'header.php';
require_once 'main.php';

$relUrl = '/phpSiteNews';

?>
<br />
<div class="container" style="margin-left:auto;margin-right:auto;">
    <!-- <h5>Liste des <?php echo $_SESSION['countRows']; ?> dernières news</h5> -->
    <p>Bonjour <?php echo $account_name; ?> ! Il y a actuellement <span class="badge badge-success"><?php echo $_SESSION['countRows']; ?></span> news. En voici la liste :</p>
    <?php foreach ($newsPage as $key => $value) {
        $text = $newsPage[$key]->{'contenu'};
        $textReduit = debutTexte($text, 190);
        $id = $newsPage[$key]->{'id'};
    ?>
    <div class="media" style="width:40rem;margin-left:auto;margin-right:auto;">
      <!-- <img src="..." class="..." alt="..."> -->
      <div class="media-body" style="border:1px solid #ddd8d8;border-radius:10px;padding:15px 25px;">
        <h5 class="mt-0"><?php echo $newsPage[$key]->{'titre'}; ?></h5>
        <p style="text-align:justify;"><?php echo $textReduit; ?></a>
        <br /><br />
        <span><strong>Auteur : <?php echo $newsPage[$key]->{'auteur'};?></strong></span>
        <form method="POST" action="details.php" style="margin:0;paddin:0;">
            <input type="hidden" name="detailID" value="<?php echo $id; ?>" />
            <input type="submit" name="details" class="btn btn-link" value="Lire la suite"/>
        </form>
      </div>
    </div>
    <br />
    <?php
    }
    ?>
</div>

<nav aria-label="nav">
  <ul class="pagination justify-content-center">
    <li <?php echo $disabledPrev; ?>>
      <a class="page-link" href="?page=<?php echo $current -1; ?>" <?php echo $tabindexPrev; ?> >Previous</a>
    </li>
    <?php foreach ($arr as $key => $value) {
    ?>
    <li <?php echo $arr[$key]->{'class'}; ?>>
      <a class="page-link" href="?page=<?php echo $arr[$key]->{'pageIndex'}; ?>"><?php echo $arr[$key]->{'pageIndex'}; ?><?php echo $arr[$key]->{'span'};; ?></a>
    </li>
    <?php
    }
    ?>
    <li <?php echo $disabledNext; ?>>
      <a class="page-link" href="?page=<?php echo $current +1; ?>" <?php echo $tabindexNext; ?> >Next</a>
    </li>
  </ul>
</nav>

<!-- <?php 
$paginator->render();
?> -->

<?php

require_once 'footer.php';

?>