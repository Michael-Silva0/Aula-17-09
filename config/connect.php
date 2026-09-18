<?php
$host = "localhost";
$user = "root";
$senha = "";
$charset = "utf8mb4";
$bd = "bd_roupas";
$tabela = "tb_roupas";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$dsn = "mysql:host=$host;dbname=$bd;charset=$charset";
