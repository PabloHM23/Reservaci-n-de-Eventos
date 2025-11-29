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

    $nombre_evento = trim($data['nombre']);
    $descripcion = trim($data['descripcion'] ?? '');
    $fecha = $data['fecha'];
    $hora_inicio = $data['hora_inicio'];
    $capacidad_max = (int)$data['capacidad'];
    $categoria_id = (int)$data['categoria'];
    $estado = 'activo';

    if (empty($nombre_evento) || empty($fecha) || empty($hora_inicio) || $categoria_id <= 0) {
        throw new Exception('Faltan campos obligatorios: Nombre, Fecha, Hora o Categoría.');
    }
    if ($capacidad_max <= 0 || $capacidad_max > 30) {
        throw new Exception('La capacidad máxima debe ser entre 1 y 30 personas.');
    }
    
    $stmt_cat = $pdo->prepare("SELECT COUNT(*) FROM Categoria WHERE id_Categoria = :id");
    $stmt_cat->execute([':id' => $categoria_id]);
    $cat_exists = $stmt_cat->fetchColumn();

    if ($cat_exists === 0) {
        throw new Exception('La categoría seleccionada no es válida. Por favor, recarga la página.');
    }

    $sql = "INSERT INTO Evento (nombre_evento, descripcion, fecha, hora_inicio, capacidad_max, estado, Usuario_id_usuario, Categoria_id_Categoria) 
            VALUES (:nombre, :desc, :fecha, :hora, :capacidad, :estado, :usuario_id, :categoria_id)";
            
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':nombre' => $nombre_evento,
        ':desc' => $descripcion,
        ':fecha' => $fecha,
        ':hora' => $hora_inicio,
        ':capacidad' => $capacidad_max,
        ':estado' => $estado,
        ':usuario_id' => $user_id,
        ':categoria_id' => $categoria_id
    ]);

    $response['success'] = true;
    $response['message'] = 'El evento "' . htmlspecialchars($nombre_evento) . '" ha sido publicado con éxito.';

} catch (PDOException $e) {
    $response['message'] = 'Error de base de datos al insertar el evento.';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>