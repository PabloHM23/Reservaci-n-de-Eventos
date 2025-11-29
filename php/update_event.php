<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Error de autenticación. Por favor, inicia sesión.']);
    exit;
}

require_once 'conexion.php'; 

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Datos enviados inválidos.");
    }

    $event_id = (int)($data['event_id'] ?? 0);
    $nombre_evento = trim($data['nombre']);
    $descripcion = trim($data['descripcion'] ?? '');
    $fecha = $data['fecha'];
    $hora_inicio = $data['hora_inicio'];
    $capacidad_max = (int)$data['capacidad'];
    $categoria_id = (int)$data['categoria'];
    $estado = $data['estado'] === 'activo' ? 'activo' : 'cancelado';

    if ($event_id <= 0) {
        throw new Exception('ID de evento no válido para la actualización.');
    }
    if (empty($nombre_evento) || empty($fecha) || empty($hora_inicio) || $categoria_id <= 0) {
        throw new Exception('Faltan campos obligatorios.');
    }
    if ($capacidad_max <= 0 || $capacidad_max > 30) {
        throw new Exception('La capacidad máxima debe ser entre 1 y 30 personas.');
    }
    
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM Evento WHERE id_evento = :id AND Usuario_id_usuario = :user_id");
    $stmt_check->execute([':id' => $event_id, ':user_id' => $user_id]);
    if ($stmt_check->fetchColumn() === 0) {
        throw new Exception('No tienes permiso para editar este evento o el evento no existe.');
    }

    $sql = "UPDATE Evento SET 
                nombre_evento = :nombre, 
                descripcion = :desc, 
                fecha = :fecha, 
                hora_inicio = :hora, 
                capacidad_max = :capacidad, 
                estado = :estado,
                Categoria_id_Categoria = :categoria_id
            WHERE id_evento = :id AND Usuario_id_usuario = :user_id";
            
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':nombre' => $nombre_evento,
        ':desc' => $descripcion,
        ':fecha' => $fecha,
        ':hora' => $hora_inicio,
        ':capacidad' => $capacidad_max,
        ':estado' => $estado,
        ':categoria_id' => $categoria_id,
        ':id' => $event_id,
        ':user_id' => $user_id
    ]);

    $response['success'] = true;
    $response['message'] = 'El evento "' . htmlspecialchars($nombre_evento) . '" ha sido actualizado con éxito.';

} catch (PDOException $e) {
    $response['message'] = 'Error de base de datos al actualizar el evento.';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>