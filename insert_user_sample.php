<?php
spl_autoload_extensions(".php");
spl_autoload_register();
require_once "vendor/autoload.php";
use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

$charset = $mysqli->get_charset();
if($charset === null) throw new Exception('Charset could be read');

$usersData = [
    ['admin', 'admin@example.com', '3WQeLoL6', 'admin'],
    ['user_1', 'user-1@example.com', 'lgRQ7qw5', 'user'],
    ['user_2', 'user-2@example.com', '6rEsglg2', 'user']
];


foreach ($usersData as $user) {
    $insertQuery = "INSERT INTO test_users (username, email, password, role) VALUES ('$user[0]', '$user[1]', '$user[2]', '$user[3]')";
    $mysqli->query($insertQuery);
}