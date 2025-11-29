<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Error: Debes iniciar sesión para reservar.']);
    exit;
}

require_once 'conexion.php'; 

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Datos de reserva inválidos.");
    }

    $event_id = (int)($data['event_id'] ?? 0);
    $espacios = (int)($data['espacios'] ?? 0);
    $current_slots = (int)($data['current_slots'] ?? 0);

    if ($event_id <= 0 || $espacios <= 0) {
        throw new Exception('Selección de evento o cantidad de cupos inválida.');
    }

    $sql_capacity = "
        SELECT 
            e.capacidad_max, e.estado,
            COALESCE(SUM(r.cupo), 0) AS cupos_ocupados
        FROM 
            Evento e
        LEFT JOIN 
            Reservacion r ON e.id_evento = r.Evento_id_evento
        WHERE
            e.id_evento = :event_id
        GROUP BY
            e.id_evento
    ";
    $stmt_cap = $pdo->prepare($sql_capacity);
    $stmt_cap->execute([':event_id' => $event_id]);
    $event_cap_data = $stmt_cap->fetch(PDO::FETCH_ASSOC);

    if (!$event_cap_data || $event_cap_data['estado'] !== 'activo') {
        throw new Exception('El evento no está activo o no existe.');
    }

    $capacidad_max = $event_cap_data['capacidad_max'];
    $cupos_ocupados = $event_cap_data['cupos_ocupados'];
    
    $new_total_occupied = $cupos_ocupados - $current_slots + $espacios;

    if ($new_total_occupied > $capacidad_max) {
        throw new Exception("No hay suficiente capacidad. Cupos disponibles: " . ($capacidad_max - $cupos_ocupados + $current_slots) . ".");
    }
    
    $is_update = $current_slots > 0;

    if ($is_update) {
        $sql = "UPDATE Reservacion SET cupo = :cupo WHERE Evento_id_evento = :event_id AND Usuario_id_usuario = :user_id";
        $message = "Reserva actualizada con éxito a {$espacios} cupo(s).";
    } else {
        $sql = "INSERT INTO Reservacion (cupo, Usuario_id_usuario, Evento_id_evento) VALUES (:cupo, :user_id, :event_id)";
        $message = "Reserva creada con éxito por {$espacios} cupo(s).";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cupo' => $espacios,
        ':event_id' => $event_id,
        ':user_id' => $user_id
    ]);

    $response['success'] = true;
    $response['message'] = $message;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
         $response['message'] = 'Ya tienes una reserva para este evento. Intenta actualizarla.';
    } else {
        $response['message'] = 'Error de base de datos al procesar la reserva.';
    }
}

echo json_encode($response);
?>