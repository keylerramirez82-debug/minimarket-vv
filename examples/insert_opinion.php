<?php
require_once __DIR__ . '/../Conexion.php';

// Ejemplo sencillo de inserción usando PDO y prepared statements
try {
    $nombre = 'Cliente de prueba';
    $motivo = 'sugerencia';
    $opinion = 'Me gusta la tienda, buena atención.';

    $stmt = $pdo->prepare('INSERT INTO opiniones (nombre, motivo, opinion) VALUES (:nombre, :motivo, :opinion)');
    $stmt->execute([
        ':nombre' => $nombre,
        ':motivo' => $motivo,
        ':opinion' => $opinion,
    ]);

    echo "Opinión insertada. ID: " . $pdo->lastInsertId();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
