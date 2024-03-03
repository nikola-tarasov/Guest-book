<?php

$host = 'localhost';
$db = 'my_db';
$user = 'root';
$pass = '';
$charset = 'utf8';


$dsh = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsh, $user, $pass);





?>