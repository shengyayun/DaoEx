<?php
include_once "vendor/autoload.php";

$pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8", 'root', 'xxxxxxxxx');

$daoEx = new \DaoEx\Build();
$daoEx->run($pdo, 'test');

