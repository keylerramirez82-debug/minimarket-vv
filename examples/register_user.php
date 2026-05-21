<?php
require_once __DIR__ . '/../Conexion.php';

// Ejemplo de registro de usuario (usa password_hash)
try {
    $nombre = 'Usuario prueba';
    $email = 'usuario@example.com';
    $password = 'secreto123';

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :password_hash)');
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password_hash' => $password_hash,
    ]);

    echo "Usuario creado. ID: " . $pdo->lastInsertId();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
