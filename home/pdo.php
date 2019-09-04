<?php

require_once '../env.php';

/* Base de données - PDO connection. */
try
{
    $pdo = Env::get_pdo();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

?>