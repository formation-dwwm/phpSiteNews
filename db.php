<?php

require_once 'env.php';

/* Base de données - PDO connection. */
try
{
  $db = Env::get_db();
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}