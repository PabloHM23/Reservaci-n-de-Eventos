<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}
require_once 'conexion.php'; 

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = trim($data['nombre']);
    $email = trim($data['email']);
    $new_password = $data['new_password'];

    if (empty($nombre) || empty($email)) {
        $response['message'] = 'El nombre y el correo no pueden estar vacíos.';
        echo json_encode($response);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Formato de correo inválido.';
        echo json_encode($response);
        exit;
    }

    $set_clauses = ['nombre = ?', 'correo_electronico = ?'];
    $params = [$nombre, $email];
    $update_message = 'Datos actualizados correctamente.';

    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $response['message'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            echo json_encode($response);
            exit;
        }
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $set_clauses[] = 'contrasena = ?';
        $params[] = $hashed_password;
        $update_message = 'Datos y contraseña actualizados correctamente.';
    }

    $sql = "UPDATE Usuario SET " . implode(', ', $set_clauses) . " WHERE id_usuario = ?";
    $params[] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $_SESSION['user_name'] = $nombre;

    $response['success'] = true;
    $response['message'] = $update_message;

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        $response['message'] = 'El correo electrónico ya está en uso.';
    } else {
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    }
} catch (Exception $e) {
    $response['message'] = 'Error interno: ' . $e->getMessage();
}

echo json_encode($response);
?>