<?php
// indique au navigateur le contenu css
header('content-type: text/css');
// Mise en cache du CSS - empêche de charger systématiquement
// header('HTTP/1.0 304 Not Modified');
// contrôle de mise en cahce
header('Cache-Control: max-age=3600, must-revalidate');

session_start();
?>
body{
  font: 14px sans-serif;
  text-align: center;
}

/* Page profil.php */
.profil {
  padding: 15px;
  font-size: 14px;
}
