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

// Obtener datos
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

// Validaciones básicas
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email y contraseña son obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido']);
    exit;
}

try {
    $db = (new Database())->getConnection();
    $userModel = new UserModel($db);

    $user = $userModel->login($email, $password);

    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_email'] = $user['correo_electronico'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'user' => $user
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
}
?>