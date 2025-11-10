<?php
// Datos de conexión
$host = "localhost";  
$usuario = "pepe";         
$contrasena = "12345";          
$base_de_datos = "sistema_eventos"; 

$conn = mysqli_connect($host, $usuario, $contrasena, $base_de_datos);


if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

echo "¡Conexión exitosa a la base de datos!";
?>
