<?php
// Datos de conexión
$host = 'sql210.infinityfree.com';
$db   = 'if0_40436864_sistema_eventos';
$user = 'if0_40436864';
$pass = 'T5PZ7TnSmp1Pzi';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Error de conexión PDO: " . $e->getMessage());
}
?>