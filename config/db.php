<?php
$pdo = new PDO(
    "mysql:host=localhost;dbname=controle_estudos;charset=utf8",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
