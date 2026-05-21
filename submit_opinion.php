<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Usa POST.']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$motivo = trim($_POST['motivo'] ?? '');
$opinion = trim($_POST['opinion'] ?? '');

if ($nombre === '' || $opinion === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos.']);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO opiniones (nombre, motivo, opinion, creado) VALUES (:nombre, :motivo, :opinion, NOW())');
    $stmt->execute([
        ':nombre' => $nombre,
        ':motivo' => $motivo ?: 'otro',
        ':opinion' => $opinion,
    ]);

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Opinión guardada correctamente.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar la opinión. Intenta de nuevo más tarde.']);
}
