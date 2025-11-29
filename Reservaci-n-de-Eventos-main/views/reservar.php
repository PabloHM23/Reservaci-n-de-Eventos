<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../php/conexion.php'; 

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'] ?? null;
$event = null;
$error_message = '';
$available_slots = 0;
$user_current_slots = 0;

if (!$event_id || !is_numeric($event_id)) {
    $error_message = "Error: ID de evento no proporcionado o inválido.";
} else {
    try {
        $sql = "
            SELECT 
                e.id_evento, e.nombre_evento, e.descripcion, e.capacidad_max, e.estado,
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
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            if ($event['estado'] !== 'activo') {
                $error_message = "Este evento no está disponible para reservación (Estado: " . ucfirst($event['estado']) . ").";
            } else {
                $available_slots = $event['capacidad_max'] - $event['cupos_ocupados'];
                
                $sql_user_slots = "SELECT cupo FROM Reservacion WHERE Evento_id_evento = :event_id AND Usuario_id_usuario = :user_id";
                $stmt_user = $pdo->prepare($sql_user_slots);
                $stmt_user->execute([':event_id' => $event_id, ':user_id' => $user_id]);
                $user_reservation = $stmt_user->fetch(PDO::FETCH_ASSOC);

                if ($user_reservation) {
                    $user_current_slots = $user_reservation['cupo'];
                }
            }
        } else {
            $error_message = "Evento no encontrado.";
        }

    } catch (PDOException $e) {
        $error_message = "Error al cargar datos del evento: " . $e->getMessage();
    }
}

$user_name = $_SESSION['user_name'] ?? ''; 
$max_reservable = $available_slots + $user_current_slots;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/nav_footer.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_welcom.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_events.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/events.css">
    <script src="../Assets/Js/home.js"></script>
    <script src="../Assets/Js/reservar.js"></script> 
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/reservar.css">
    <title>Agora Reservación</title>
    <link rel="shortcut icon" href="../Assets/img/logo.png" type="image/x-icon">
</head>
<body>
    <div class="nav">
        <div class="logo"><a href="../index.php"><img src="../Assets/img/title.png"></a></div>
        <nav class="nav_links">
            <ul class="nav-menus">
                <li class="menu">
                    <button id="btn" class="menu-btn" aria-haspopup="true" aria-expanded="false">
                        Eventos ▾
                    </button>
                    <div class="menu-content">
                        <a href="eventos.php">Eventos próximos y pasados</a>
                        <a href="crear_evento.php">Crear un evento</a>
                        <a href="mi_evento.php">Mis eventos</a>
                    </div>
                </li>
                <li class="menu">
                    <button id="btn" class="menu-btn" aria-haspopup="true" aria-expanded="false">
                        Reservaciones ▾
                    </button>
                    <div class="menu-content">
                        <a href="reservar.php">Reservar evento</a>
                        <a href="historial_r.php">Historial de reservaciones</a>
                    </div>
                </li>
                <li><button class="menu-btn"><a href="calendario.php">Calendario</a></button></li>
            </ul>
        </nav>
    </div>
    <br>
    <div class="container">
        <?php if ($error_message || !$event): ?>
            <div class="error-panel" style="padding: 20px; text-align: center; color: #d32f2f; background-color: #fcebeb; border-radius: 8px;">
                <h1 class="main-title">Error al cargar la reserva</h1>
                <p><?php echo $error_message; ?></p>
                <a href="eventos.php" style="color: #4a5f8f; text-decoration: underline; margin-top: 10px; display: inline-block;">Volver a la lista de eventos</a>
            </div>
        <?php else: ?>
        <form id="reservationForm" data-event-id="<?php echo $event_id; ?>" data-max-reservable="<?php echo $max_reservable; ?>">
            <div class="reservation-panel">
                <h1 class="main-title">Reservación: <?php echo htmlspecialchars($event['nombre_evento']); ?></h1>
                <p class="subtitle" style="margin-bottom: 20px;"><?php echo htmlspecialchars($event['descripcion'] ?? 'Sin descripción.'); ?></p>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="espacios">Espacios a reservar/modificar:</label>
                    <div class="number-control">
                        <input type="number" id="espacios" name="espacios" 
                            value="<?php echo $user_current_slots > 0 ? $user_current_slots : 1; ?>" 
                            min="1" 
                            max="<?php echo $max_reservable; ?>" 
                            data-current-slots="<?php echo $user_current_slots; ?>"
                            required>
                        <div class="control-buttons">
                            <button type="button" class="control-btn" data-action="increment">▲</button>
                            <button type="button" class="control-btn" data-action="decrement">▼</button>
                        </div>
                    </div>
                    <span class="error-txt" id="error-espacios"></span>
                    <span class="helper-text" id="helper-text">
                        <?php if ($user_current_slots > 0): ?>
                            Actualmente tienes reservado: <?php echo $user_current_slots; ?> lugar(es). Lugares adicionales disponibles: <?php echo $available_slots; ?>.
                        <?php else: ?>
                            Lugares disponibles: <?php echo $available_slots; ?>. Capacidad máxima: <?php echo $event['capacidad_max']; ?>.
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" id="btnReservar" class="btn-primary">
                        <?php echo $user_current_slots > 0 ? 'Actualizar reserva' : 'Reservar lugares'; ?>
                    </button>
                    <button type="button" class="btn-secondary" onclick="window.location.href='eventos.php'">Cancelar</button>
                </div>
            </div>
        </form>
        <div class="promo-panel">
            <div class="promo-content">
                <h2 class="promo-title">¡Reserva y disfruta lo mejor!</h2>
                <p class="promo-text">Relájate un momento con tus amigos, familiares, pareja y quien tú quieras.</p>
                <p class="promo-highlight">Solo aquí, en Agora</p>
            </div>
            <div class="promo-overlay"></div>
        </div>
        <?php endif; ?>
    </div>
    <br>
    <div id="footer">
        <footer class="footer">
            <div class="footer_content">
                <div class="name">
                    <img src="../Assets/img/title.png">
                </div>
                <div class="contact_info">
                    Contáctanos 
                    <br>
                    Av. Calle falso #1234
                    <br>
                    <a href="mailto: Agoraeventos@correofalso.com">Agoraeventos@correofalso.com</a>
                    <br>
                    012 000 45-150 
                    <br>
                    <br>
                    © 2025 Agora Todos los derechos reservados.
                </div>
                <div class="div_3"></div>
            </div>
        </footer>
    </div>
</body>
</html>