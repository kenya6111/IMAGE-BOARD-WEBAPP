<?php
use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();
//file_get_contents — ファイルの内容を全て文字列に読み込む
$result = $mysqli->query(file_get_contents(__DIR__ . '/Examples/cars-setup.sql'));

if($result === false) throw new Exception('Could not execute query.');
else print("Successfully ran all SQL setup queries.".PHP_EOL);