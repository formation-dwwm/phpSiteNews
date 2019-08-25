<?php

require_once 'header.php';
require_once 'pdo.php';

/* COMMUNS UTILISATEURS */
if (isset($_SERVER['REQUEST_URI'])) {
  $activeAccueil = ($_SERVER['REQUEST_URI'] == '/tpnews/home/accueil.php') ? 'active' : '';
  $activeProfil = ($_SERVER['REQUEST_URI'] == '/tpnews/home/edit.php') ? 'active' : '';
  $activeAdmin = ($_SERVER['REQUEST_URI'] == '/tpnews/admin/admin.php') ? 'active' : '';
  $activeCreate = ($_SERVER['REQUEST_URI'] == '/tpnews/home/create.php') ? 'active' : ''; 
}

/* PAGE PROFIL */

if (isset($_SERVER['REQUEST_URI']) == '/tpnews/home/profil.php') {
  
  $res = $loginCookie->get_account($db);
  // $loginCookie->enabled_account($account_id, $db);
  // var_dump($res);
  
  $account_enabled = ($res['account_enabled'] == '1') ? 'actif' : 'inactif';
  $account_name = $res['account_name'];
  $account_email = $res['account_email'];
  $account_password = $res['account_email'];
  $confirmed_at = $res['confirmed_at'];
  $confirmation_token = $res['confirmation_token'];

  $account_confirmed = date('Y-m-d H:i:s');
  $confirmed_at = $account_confirmed;
  
}

/* PAGE ADMIN && UTILISATEURS */

// requête toute la base de donnée pour les actions modifier et supprimer
$req = $pdo->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY dateModif DESC;');
$req->execute();
$req->setFetchMode(PDO::FETCH_OBJ);
$news = $req->fetchAll();
// var_dump($news);

// requête la base de données pour comptabilisé le nombres d'entrée (table)
$countRows = $pdo->query('SELECT COUNT(*) FROM news;');
$countRows->setFetchMode(PDO::FETCH_OBJ);
$count = $countRows->fetchAll();
$_SESSION['countRows'] = $count[0]->{'COUNT(*)'};

// initialise la page à chaque action - "pourra être dispensé"
$inputAuteur = '';
$inputTitre = '';
$inputContenu = '';

// Formulaire d'entrée pour l'admin - articles du sites
if (!empty($_POST['auteur']) || !empty($_POST['titre']) || !empty($_POST['contenu'])) {
    // Requêtre d'entrée de l'article dans la base de données
    if(empty($_SESSION['id']) && isset($_POST['ajouter'])) {
            $auteur = $_POST['auteur'];
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $dateAjout = date('Y-m-d H:i:s');
            $dateModif = $dateAjout;
            
            $sql1 = 'INSERT INTO news (auteur, titre, contenu, dateAjout, dateModif) VALUES (?, ?, ?, ?, ?)';
            $req1 = $pdo->prepare($sql1);
            $req1->execute(array($auteur, $titre, $contenu, $dateAjout, $dateModif));
            // initialisation des données du navigateur
            unset($_POST);
            // Regénère l'affiche - Single Page
            header('Refresh: 0');
            exit();
    }
    // Requête de modification de l'article dans la base de données
    if (!empty($_SESSION['id']) && isset($_POST['ajouter'])) {
        $id = $_SESSION['id'];
        $inputAuteur = $_POST['auteur'];
        $inputTitre = $_POST['titre'];
        $inputContenu = $_POST['contenu'];
        // $dateAjout = $news[$id]->{'dateAjout'};
        $dateModif = date('Y-m-d H:i:s');

        $sql2 = 'UPDATE news SET auteur=:inputAuteur,titre=:inputTitre,contenu=:inputContenu,dateModif=:dateModif WHERE id=:id;';
        $req2 = $pdo->prepare($sql2);
        $req2->execute(array(
            ':inputAuteur' => $account_name,
            ':inputTitre' => $inputTitre,
            ':inputContenu' => $inputContenu,
            ':dateModif' => $dateModif,
            ':id' => $id
        ));
        // initialisation des données du navigateur
        $inputAuteur = '';
        $inputTitre = '';
        $inputContenu = '';
        unset($_SESSION['id']);
        unset($_POST);
        // Regénère l'affiche - Single Page
        header('Refresh: 0');
        exit();
    }
}

/* PAGE ADMIN */

// Récupére les données à modifier vers input sur la même page admin.php
if (!empty($_POST['newId']) && !empty($_POST['modifier'])) {
    $idMod = $_POST['newId'];
    $_SESSION['id'] = $idMod;
    // echo $_SESSION['id'];
    // var_dump($news);
    if($news) {
        for ($i = 0; $i < count($news); $i++) {
            if ($news[$i]->{'id'} == $idMod) {
                $inputAuteur = $news[$i]->{'auteur'};
                $inputTitre = $news[$i]->{'titre'};
                $inputContenu = $news[$i]->{'contenu'};
                $dateAjout = $news[$i]->{'dateAjout'};
                $dateModif = $news[$i]->{'dateModif'};
            }
        }
    }
    unset($_POST);
}
// Supprime un article avec son ID
if (!empty($_POST['newId']) && !empty($_POST['supprimer'])) {
    $idSup = $_POST['newId'];
    $sql3 = 'DELETE FROM news WHERE id=:id;';
    $req3 = $pdo->prepare($sql3);
    $req3->execute(array(
        ':id' => $idSup
    ));
    // initialisation des données du navigateur
    $inputAuteur = '';
    $inputTitre = '';
    $inputContenu = '';
    unset($_POST);
    // Regénère l'affiche - Single Page
    header('Location: admin.php');
}

// Nettoye les données du navigateur
if (isset($_POST['vider'])) {
    $inputAuteur = '';
    $inputTitre = '';
    $inputContenu = '';
    unset($_SESSION['id']);
    unset($_POST);
}

/* PAGE ACCEUIL */
// limite le texte du contenu de l'article - 190 max
function debutTexte($textData, $long) {
    if (strlen ($textData) <= $long) {
        return $textData;
    } else {
        $debut = substr($textData, 0, $long);
        $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
        return $debut;
    }
}

/* PAGE DETAILS */
// Envoi les informations de l'article ID vers Page details.php
if (isset($_POST['detailID'])) {
    $detailsID = $_POST['detailID'];
    // echo $detailsID;
    // var_dump($news);
    if($news) {
        for ($j = 0; $j < count($news); $j++) {
            if ($news[$j]->{'id'} == $detailsID) {
                $texteAuteur = $news[$j]->{'auteur'};
                $texteTitre = $news[$j]->{'titre'};
                $texteContenu = $news[$j]->{'contenu'};
            }
        }
    }
    unset($_POST);
}

/* PAGINATION PAGES ADMIN && ACCEUIL */
// Tableau de tous les cas de figure de la nav
$classArr = array(
  'classActive' => 'class="page-item active" aria-current="page"',
  'spanActive' => '<span class="sr-only">(current)</span>',
  'class' => 'class="page-item"',
  'span' =>''
);
$prev = array(
  'tabindex' =>'tabindex="-1" aria-disabled="true"',
  'disabled' => 'class="page-item disabled"',
  'tabindexActive' =>'',
  'disabledActive' => 'class="page-item"'
);
$next = array(
  'tabindex' =>'tabindex="-1" aria-disabled="true"',
  'disabled' => 'class="page-item disabled"',
  'tabindexActive' =>'',
  'disabledActive' => 'class="page-item"'
);

// Trouve le nombre de lignes sql en session pour déterminer le nombre d'url de pages
$countSQL = $_SESSION['countRows'];
if (($countSQL % 3) == 0) {
  $countPage = (int)($_SESSION['countRows'] / 3);
} else {
  $countPage = (int)($_SESSION['countRows'] / 3) + 1;
}
// Résultats de quantité de page
$page = array();
for ($k = 0; $k < $countPage; $k++) {
    array_push($page, ($k + 1));
}

// Retrouve le numéro de page dans l'url de la page web
$current = (!empty($_GET['page']) ? $_GET['page'] : 1);
$currentMax = $countPage;

// Récupére des lignes de table à afficher dans un ARRAY d'OBJET
$limite = 3;
$debut = ($current - 1) * $limite;
$req4 = $pdo->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY dateModif DESC LIMIT :limite OFFSET :debut;');
$req4->bindValue('limite', $limite, PDO::PARAM_INT);
$req4->bindValue('debut', $debut, PDO::PARAM_INT);
$req4->execute();
$req4->setFetchMode(PDO::FETCH_OBJ);
$newsPage = $req4->fetchAll();


// Détermine un tableau des classes actives pour l'affichage des chiffres
// en fonction de l'url de page : $current

if ($current > 3) {
  $max = $current - 2;
  $temp = $current;
} else {
  $max = 1;
  $temp = $currentMax;
}
for ($n = $max; $n <= $temp; $n++) {
  $pageIndex = $n;
  if ($pageIndex == $current) {
    $class = $classArr['classActive'];
    $span = $classArr['spanActive'];
  } else {
    $class = $classArr['class'];
    $span = $classArr['span'];
  }
  $arr [] = (object) [
    'pageIndex' => $pageIndex,
    'class' => $class,
    'span' => $span
  ];
}
// var_dump($arr);

// Détermine un tableau des classes actives pour l'affichage des PREV et NEXT
// en fonction de l'url de page : $current
for ($m = 0; $m < $currentMax; $m++) {
  if ($page[$m] < 4) {
    if ($current < 2) {
      $tabindexPrev = $prev['tabindex'];
      $disabledPrev = $prev['disabled'];
      $tabindexNext = $next['tabindex'];
      $disabledNext = $next['disabled'];
    } else {
      $tabindexPrev = $prev['tabindexActive'];
      $disabledPrev = $prev['disabledActive'];
      $tabindexNext = $next['tabindex'];
      $disabledNext = $next['disabled'];
    }
  }
  if ($page[$m] > 1) {
    if ($current < $currentMax) {
      $tabindexPrev = $prev['tabindex'];
      $disabledPrev = $prev['disabled'];
      $tabindexNext = $next['tabindexActive'];
      $disabledNext = $next['disabledActive'];
    } else if ($current = $currentMax) {
      $tabindexPrev = $prev['tabindexActive'];
      $disabledPrev = $prev['disabledActive'];
      $tabindexNext = $next['tabindex'];
      $disabledNext = $next['disabled'];
    }
  }
}
// var_dump($arr);


require_once 'footer.php';

?>