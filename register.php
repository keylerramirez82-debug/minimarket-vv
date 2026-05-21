<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Usa POST.']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($nombre === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos.', 'errors' => ['nombre','email','password']]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Correo electrónico no válido.']);
    exit;
}

try {
    // Verificar si el email ya existe
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Este correo ya está registrado. Usa otro o inicia sesión.']);
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :password_hash)');
    $insert->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password_hash' => $password_hash,
    ]);

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Registro exitoso. Ya puedes iniciar sesión.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario. Por favor intenta de nuevo más tarde.']);
}
