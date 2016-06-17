<?php
$db_hostname = 'localhost';
$db_database = 'workers';
$db_username = 'vita';
$db_password = 'vita';

$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_error) die($connection->connect_error);
$connection->set_charset("utf8");

define('NEWS_ON_PAGE', 2);