<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Usermodel.php';

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$nombre = trim($input['nombre'] ?? '');
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

// Validaciones rápidas
if (strlen($nombre) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

try {
    $db = (new Database())->getConnection();
    $userModel = new UserModel($db);

    if ($userModel->emailExists($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email ya registrado']);
        exit;
    }

    $userId = $userModel->create($nombre, $email, $password);
    
    if ($userId) {
        echo json_encode(['success' => true, 'message' => 'Registro exitoso', 'userId' => $userId]);
    } else {
        throw new Exception('Error al crear usuario');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}
?>