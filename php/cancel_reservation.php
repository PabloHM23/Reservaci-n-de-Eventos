<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
    exit;
}

require_once 'conexion.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $event_id = $data['event_id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    if ($event_id <= 0) {
        throw new Exception("ID de evento inválido.");
    }

    $stmtCheck = $pdo->prepare("SELECT id_reservacion FROM Reservacion WHERE Usuario_id_usuario = :uid AND Evento_id_evento = :eid");
    $stmtCheck->execute([':uid' => $user_id, ':eid' => $event_id]);
    
    if ($stmtCheck->rowCount() === 0) {
        throw new Exception("No se encontró la reservación o ya fue cancelada.");
    }

    $stmtDelete = $pdo->prepare("DELETE FROM Reservacion WHERE Usuario_id_usuario = :uid AND Evento_id_evento = :eid");
    $stmtDelete->execute([':uid' => $user_id, ':eid' => $event_id]);

    echo json_encode(['success' => true, 'message' => 'Reservación cancelada y espacios liberados.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>