<?php

/* Base de données - PDO connection. */
try
{
  $db = new PDO('mysql:host=localhost:3305;dbname=phpAuth', 'cursusdev', 'cursus@2019');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}