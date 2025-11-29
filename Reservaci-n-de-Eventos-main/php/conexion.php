<?php
// Datos de conexión
$host = 'localhost';
$db   = 'sistema_eventos';
$user = 'pepe';
$pass = '12345';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     // 🛑 ¡ASEGÚRATE DE QUE NO HAY NINGÚN 'ECHO' AQUÍ!
} catch (\PDOException $e) {
     // Esto maneja el error de conexión
     die("Error de conexión PDO: " . $e->getMessage());
}
?>