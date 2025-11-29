<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../php/conexion.php'; 

$user_id = $_SESSION['user_id'];
$events = [];
$error_message = '';

function format_event_date($date_str, $time_str) {
    if (!$date_str) return '';
    setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');
    $timestamp = strtotime($date_str);
    $date_formatted = strftime('%A, %d de %B de %Y', $timestamp);
    
    return $date_formatted . ' a las ' . htmlspecialchars($time_str);
}

try {
    $sql = "
        SELECT 
            e.id_evento,
            e.nombre_evento,
            e.fecha,
            e.hora_inicio,
            e.capacidad_max,
            e.estado,
            c.nombre_categoria,
            COALESCE(SUM(r.cupo), 0) AS asistentes
        FROM 
            Evento e
        JOIN 
            Categoria c ON e.Categoria_id_Categoria = c.id_Categoria
        LEFT JOIN 
            Reservacion r ON e.id_evento = r.Evento_id_evento
        WHERE 
            e.Usuario_id_usuario = :user_id
        GROUP BY 
            e.id_evento
        ORDER BY 
            e.fecha DESC, e.hora_inicio DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error al cargar tus eventos: " . $e->getMessage();
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
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/mievent.css">
    <script src="../Assets/Js/home.js"></script>
    <script src="../Assets/Js/mi_evento_actions.js"></script>
    <title>Agora Mi evento</title>
    <link rel="shortcut icon" href="../Assets/img/logo.png" type="image/x-icon">

    <style>
        .actions-dropdown-container {
            position: relative;
            display: inline-block;
        }

        .actions-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 6px;
            padding: 5px 0;
        }

        .actions-dropdown-content a,
        .actions-dropdown-content button {
            color: #000;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
            cursor: pointer;
        }

        .actions-dropdown-content a:hover,
        .actions-dropdown-content button:hover {
            background-color: #ddd;
        }

        .actions-dropdown-content .action-delete {
            color: #d32f2f;
        }
        .actions-dropdown-content .action-delete:hover {
            background-color: #ffcdd2;
        }
        .actions-dropdown.active + .actions-dropdown-content {
            display: block;
        }
    </style>
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
            <h1>Mis eventos</h1>
            <p class="subtitle">Gestiona y haz seguimiento de tus creaciones</p>
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
                <button class="tab-btn">Canceladas</button>
            </div>
            
            <div class="sort-dropdown">
                <select id="sort-select">
                    <option>Fecha (Reciente)</option>
                    <option>Fecha (Antiguo)</option>
                    <option>Nombre (A-Z)</option>
                    <option>Nombre (Z-A)</option>
                    <option>Más asistentes</option>
                </select>
            </div>
        </div>

        <div class="events-container">
            <div class="events-header">
                <div class="header-col">Evento</div>
                <div class="header-col">Asistentes</div>
                <div class="header-col">Estado / Visible</div>
            </div>

            <?php if (empty($events)): ?>
                <div class="no-events">
                    <p>Aún no has creado ningún evento. <a href="crear_evento.php">¡Crea tu primer evento ahora!</a></p>
                </div>
            <?php endif; ?>

            <?php foreach ($events as $event): 
                $is_active = $event['estado'] === 'activo' && strtotime($event['fecha']) >= time();
                $checkbox_checked = $is_active ? 'checked' : '';
                $estado_clase = $event['estado'];
            ?>
            <div class="event-card" data-estado="<?php echo $estado_clase; ?>">
                <div class="event-info">
                    <img style="width: 60px; height: 60px; object-fit: cover; flex-shrink: 0; border-radius: 8px;" src="https://placehold.co/100x100/555/FFF?text=<?php echo substr($event['nombre_categoria'], 0, 1); ?>" alt="<?php echo htmlspecialchars($event['nombre_categoria']); ?>" class="event-image">
                    <div class="event-details" style="flex: 1; min-width: 0; overflow: hidden;">
                        <h3 class="event-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; color: #000; font-weight: 600;"><?php echo htmlspecialchars($event['nombre_evento']); ?></h3>
                        <p class="event-date">
                            <?php echo format_event_date($event['fecha'], $event['hora_inicio']); ?>
                            <span class="event-category">(<?php echo htmlspecialchars($event['nombre_categoria']); ?>)</span>
                        </p>
                    </div>
                </div>
                
                <div class="event-attendees">
                    <span class="attendees-count"><?php echo $event['asistentes']; ?> / <?php echo $event['capacidad_max']; ?></span>
                </div>
                
                <div class="event-visibility">
                    <label class="toggle-switch" title="Cambiar visibilidad/estado">
                        <input type="checkbox" data-event-id="<?php echo $event['id_evento']; ?>" <?php echo $checkbox_checked; ?> <?php echo ($event['estado'] !== 'activo' && $event['estado'] !== 'cancelado') ? 'disabled' : ''; ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="event-status-label <?php echo $estado_clase; ?>">
                        <?php echo ucfirst($estado_clase); ?>
                    </span>
                </div>
                
                <div class="event-actions">
                    <div class="actions-dropdown-container">
                        <button class="actions-dropdown" type="button" data-event-id="<?php echo $event['id_evento']; ?>">
                            Acciones
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                                <path d="M6 9L1 4h10z"/>
                            </svg>
                        </button>
                        <div class="actions-dropdown-content">
                            <a href="editar_evento.php?id=<?php echo $event['id_evento']; ?>" class="action-edit">Editar Evento</a>
                            
                            <button type="button" class="action-delete" data-event-id="<?php echo $event['id_evento']; ?>">Eliminar Evento</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <div class="pagination">
            <button class="pagination-btn" disabled>
                <span>‹</span> Anterior
            </button>
            <button class="pagination-number active">1</button>
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