<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-07
 * Time: 09:53
 */

require "models/Database.php";

$db = Database::connect();
$sql = fopen("db_projektmanagement.sql", "r");

$sql = fread($sql, filesize("db_projektmanagement.sql"));

$stmt = $db->prepare($sql);
$stmt->execute();

?>

