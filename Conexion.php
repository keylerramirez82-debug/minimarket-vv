<?php
// Conexión a la base de datos usando PDO
// Edita estos parámetros si tu servidor MySQL usa credenciales distintas
$host = 'localhost';
$db   = 'minimarket_vv';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // No revelar detalles sensibles en producción
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}

// Uso: incluir este archivo y usar la variable $pdo
// Ejemplo: require_once 'Conexion.php';
// $stmt = $pdo->prepare('SELECT * FROM usuarios');
?>