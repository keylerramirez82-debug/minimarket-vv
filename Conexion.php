<?php
// Configuración de los parámetros del servidor local (XAMPP)
$host = "localhost";
$user = "root";       // Usuario por defecto de XAMPP
$pass = "";           // Contraseña por defecto (vacía)
$db   = "minimarket_vv"; // El nombre de la base de datos que creamos

// Crear la conexión utilizando la extensión mysqli
$conexion = mysqli_connect($host, $user, $pass, $db);

// Verificar si la conexión fue exitosa
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar el conjunto de caracteres a UTF-8 para evitar problemas con acentos (ñ, á, etc.)
mysqli_set_charset($conexion, "utf8");

// Si llega aquí, la conexión es exitosa
// echo "Conexión establecida correctamente"; 
?>