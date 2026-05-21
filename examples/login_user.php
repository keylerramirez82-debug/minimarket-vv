<?php
require_once __DIR__ . '/../Conexion.php';

// Ejemplo simple de verificación de credenciales
try {
    $email = 'usuario@example.com';
    $password = 'secreto123';

    $stmt = $pdo->prepare('SELECT id, nombre, password_hash FROM usuarios WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        echo "Login correcto. Bienvenido: " . $user['nombre'] . " (ID: " . $user['id'] . ")";
    } else {
        echo "Credenciales inválidas.";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
