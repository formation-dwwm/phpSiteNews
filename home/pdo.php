<?php

/* Base de données - PDO connection. */
try
{
    $pdo = new PDO('mysql:host=localhost:3305;dbname=tpnews', 'cursusdev', 'cursus@2019');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

?>