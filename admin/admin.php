<?php

require_once '../home/header.php';
require_once '../home/main.php';

$relUrl = '/phpSiteNews';

if (isset($_COOKIE['auth_cookie']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    // echo 'Tu est connecté en tant qu\'administrateur';
} else {
  header('Location: ' . $relUrl . '/login.php');
}

?>
  <br />
  <form method="POST" style="width:700px;margin-left:auto;margin-right:auto">
      <div class="form-group">
          Titre :<input type="text" class="form-control" name="titre" value="<?php echo $inputTitre; ?>" />
          <?php if (isset($emptyTitre)) { echo $emptyTitre; } ?>
      </div>
      <div class="form-group">
          <label for="contenu">Contenu :</label>
          <textarea id="contenu" class="form-control" name="contenu" rows="5" cols="33"><?php echo $inputContenu; ?></textarea>
          <?php if (isset($emptyContenu)) { echo $emptyContenu; } ?>
      </div>
      <input type="submit" class="btn btn-success" name="vider" value="vider" />
      <input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter" />
  </form>
  <hr />
  <div class="container">
    <p>Il y a actuellement <span class="badge badge-success"><?php echo $_SESSION['countRows']; ?></span> news. En voici la liste :</p>
    <table class="table table-bordered" style="margin-left:auto;margin-right:auto;width:850px;">
      <thead class="thead-light">
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Auteur</th>
          <th scope="col">Titre</th>
          <th scope="col">Date de création</th>
          <th scope="col">Dernière modif</th>
          <th scope="col" collapse="2">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($newsPage as $key => $value) {
          $dateAjout = $newsPage[$key]->{'dateAjout'};
          $newDateAjout = date('d/m/Y', strtotime($dateAjout));
          $dateModif = $newsPage[$key]->{'dateModif'};
          $newDateModif = date('d/m/y H:i', strtotime($dateModif));
          $newId = $newsPage[$key]->{'id'};
        ?>
        <tr>
          <td><?php echo $newId ?></td>
          <td><?php echo $newsPage[$key]->{'auteur'}; ?></td>
          <td><?php echo $newsPage[$key]->{'titre'}; ?></td>
          <td><?php echo $newDateAjout; ?></td>
          <td><?php echo $newDateModif; ?></td>
          <td>
              <form method="POST">
                  <input type="submit" class="btn btn-warning" name="modifier" value="modifier" />
                  <input type="submit" class="btn btn-danger" name="supprimer" value="X" />
                  <input type="hidden" class="btn btn-warning" name="newId" value="<?php echo $newId; ?>" />
              </form>
          </td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
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
  </div>
</body>
</html>