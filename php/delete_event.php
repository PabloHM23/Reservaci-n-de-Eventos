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
    $event_id = (int)($data['event_id'] ?? 0);

    if ($event_id <= 0) {
        throw new Exception("ID de evento inválido.");
    }

    $sql = "DELETE FROM Evento WHERE id_evento = :event_id AND Usuario_id_usuario = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':event_id' => $event_id, ':user_id' => $user_id]);

    if ($stmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Evento eliminado correctamente.';
    } else {
        throw new Exception('No se pudo eliminar el evento. El evento no existe o no tienes permiso.');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    $response['message'] = 'Error de base de datos al intentar eliminar.';
}

echo json_encode($response);
?>