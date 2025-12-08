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
    $new_status = $data['status'] ?? ''; 
    $user_id = $_SESSION['user_id'];

    if ($event_id <= 0 || !in_array($new_status, ['activo', 'cancelado'])) {
        throw new Exception("Datos inválidos.");
    }

    $sql = "UPDATE Evento 
            SET estado = :estado 
            WHERE id_evento = :event_id AND Usuario_id_usuario = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':estado' => $new_status,
        ':event_id' => $event_id,
        ':user_id' => $user_id
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Visibilidad actualizada.']);
    } else {
        
        $check = $pdo->prepare("SELECT id_evento FROM Evento WHERE id_evento = ? AND Usuario_id_usuario = ?");
        $check->execute([$event_id, $user_id]);
        
        if ($check->fetch()) {
            echo json_encode(['success' => true, 'message' => 'El estado ya era el solicitado.']);
        } else {
            throw new Exception("No se pudo actualizar. Verifica que el evento sea tuyo.");
        }
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>