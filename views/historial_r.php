<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../php/conexion.php'; 
$user_id = $_SESSION['user_id'];
$reservations = [];
$error_message = '';

function format_reservation_date($date_str, $time_str) {
    if (!$date_str) return '';
    setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');
    $timestamp = strtotime($date_str);
    $date_formatted = strftime('%A, %d de %B', $timestamp);
    
    return $date_formatted . ', ' . htmlspecialchars($time_str);
}
try {
    $sql = "
        SELECT 
            r.cupo,
            r.fecha_inscripcion,
            e.nombre_evento,
            e.fecha AS event_date,
            e.hora_inicio,
            e.estado,
            c.nombre_categoria
        FROM 
            Reservacion r
        JOIN 
            Evento e ON r.Evento_id_evento = e.id_evento
        JOIN
            Categoria c ON e.Categoria_id_Categoria = c.id_Categoria
        WHERE 
            r.Usuario_id_usuario = :user_id
        ORDER BY 
            e.fecha DESC, e.hora_inicio DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error al cargar tu historial de reservaciones: " . $e->getMessage();
}
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
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/history.css">
    <title>Agora Historial</title>
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
        <header class="page-header">
            <h1>Mis reservaciones</h1>
            <p class="subtitle">Gestiona y haz seguimiento de tus eventos a los que asistirás</p>
        </header>
        
        <?php if ($error_message): ?>
            <div style="color: red; text-align: center; margin: 20px; padding: 10px; border: 1px solid red; background-color: #ffeaea;">
                Error: <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="filters-section">
            <div class="filter-tabs">
                <button class="tab-btn active">Próximas</button>
                <button class="tab-btn">Finalizadas</button>
                <button class="tab-btn">Cancelados</button>
            </div>
            
            <div class="sort-dropdown">
                <select id="sort-select">
                    <option>Fecha (Reciente)</option>
                    <option>Fecha (Antiguo)</option>
                    <option>Nombre (A-Z)</option>
                    <option>Nombre (Z-A)</option>
                </select>
            </div>
        </div>

        <div class="reservations-container">
            <div class="reservations-header">
                <div class="header-col">Reservación</div>
                <div class="header-col">Espacios reservados</div>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="no-reservations" style="text-align: center; padding: 30px; background-color: white; border-radius: 8px;">
                    <p>Aún no tienes reservaciones activas. <a href="eventos.php" style="color: #4a5f8f; text-decoration: underline;">Explora eventos para reservar.</a></p>
                </div>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): 
                    $event_title = htmlspecialchars($reservation['nombre_evento'] . ' - ' . $reservation['nombre_categoria']);
                    $event_date_time = format_reservation_date($reservation['event_date'], $reservation['hora_inicio']);
                    $event_status = $reservation['estado'];
                ?>
                <div class="reservation-card" data-status="<?php echo $event_status; ?>">
                    <div class="reservation-info">
                        <img src="https://placehold.co/100x100/4a5f8f/ffffff?text=<?php echo substr($reservation['nombre_categoria'], 0, 1); ?>" alt="<?php echo htmlspecialchars($reservation['nombre_categoria']); ?>" class="reservation-image">
                        <div class="reservation-details">
                            <h3 class="reservation-title"><?php echo $event_title; ?></h3>
                            <p class="reservation-date"><?php echo $event_date_time; ?></p>
                            <p class="status-label" style="color: <?php echo ($event_status === 'activo' ? 'green' : ($event_status === 'cancelado' ? 'red' : 'gray')); ?>;">
                                Estado del Evento: <?php echo ucfirst($event_status); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="reservation-spaces">
                        <span class="spaces-count"><?php echo htmlspecialchars($reservation['cupo']); ?></span>
                    </div>
                    
                    <div class="reservation-actions">
                        <button class="actions-dropdown">
                            Acciones
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                                <path d="M6 9L1 4h10z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <button class="pagination-btn" disabled>
                <span>‹</span> Anterior
            </button>
            <button class="pagination-number active">1</button>
            <button class="pagination-number">2</button>
            <button class="pagination-btn">
                Siguiente <span>›</span>
            </button>
        </div>
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