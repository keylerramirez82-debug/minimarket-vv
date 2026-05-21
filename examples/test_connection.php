<?php
require_once __DIR__ . '/../Conexion.php';

// Prueba rápida de conexión
try {
    echo "Conexión PDO establecida correctamente a la base de datos: " . ($pdo ? 'OK' : 'FAIL');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
