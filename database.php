<?php
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_error) die($connection->connect_error);

$query = "CREATE TABLE news (
id INT NOT NULL AUTO_INCREMENT,
title TEXT NOT NULL,
content TEXT NOT NULL,
PRIMARY KEY (id)
)";

//$query = "DROP TABLE news";

$result = $connection->query($query);
if (!$result) die($connection->error);
?>