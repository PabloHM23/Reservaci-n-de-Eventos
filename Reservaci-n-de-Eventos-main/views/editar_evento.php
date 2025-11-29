<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$event_id = $_GET['id'] ?? null;
if (!$event_id || !is_numeric($event_id)) {
    header('Location: mi_evento.php');
    exit;
}

require_once '../php/conexion.php'; 

$user_id = $_SESSION['user_id'];
$event = null;
$categories = [];
$error_message = '';

try {
    $stmt_cat = $pdo->query("SELECT id_Categoria, nombre_categoria FROM Categoria ORDER BY nombre_categoria");
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM Evento WHERE id_evento = :id AND Usuario_id_usuario = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $event_id, ':user_id' => $user_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        $error_message = "Error: Evento no encontrado o no tienes permiso para editarlo.";
    }

} catch (PDOException $e) {
    $error_message = "Error al cargar datos de la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/nav_footer.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_welcom.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_events.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/edit_event.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../Assets/Js/home.js"></script>
    <script src="../Assets/Js/edit_event.js"></script>
    <title>Editar Evento</title>
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
    <div class="modal-overlay">
        <div class="modal-container">
            <?php if ($error_message): ?>
                <div class="error-box" style="padding: 20px; color: red; background-color: #fcebeb; border: 1px solid #f09595; margin-bottom: 20px; border-radius: 8px;">
                    <h2>Error de Acceso</h2>
                    <p><?php echo $error_message; ?></p>
                    <button onclick="window.location.href='mi_evento.php'" style="margin-top: 10px; padding: 8px 15px;">Volver a Mis Eventos</button>
                </div>
            <?php elseif ($event): ?>
            
            <div class="modal-header">
                <div class="header-title">
                    <svg class="icon-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    <h1>Edita y guarda tu evento: <?php echo htmlspecialchars($event['nombre_evento']); ?></h1>
                </div>
                <p class="subtitle">Completa la información para dar vida a tu evento perfecto.</p>
            </div>

            <form class="event-form" id="editEventForm" data-event-id="<?php echo $event_id; ?>">
                <section class="form-section">
                    <h2 class="section-title">Información básica</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre"
                                value="<?php echo htmlspecialchars($event['nombre_evento']); ?>"
                                placeholder='"Concierto benéfico" Max. 33 caracteres'
                                maxlength="33"
                                required
                            >
                            <div class="error-txt" id="error-nombre"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria" required>
                                <option value="">Selecciona una categoría</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['id_Categoria']); ?>"
                                        <?php echo ($cat['id_Categoria'] == $event['Categoria_id_Categoria']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="error-txt" id="error-categoria"></div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h2 class="section-title">Descripción</h2>
                    <div class="form-group">
                        <textarea 
                            id="descripcion" 
                            name="descripcion"
                            rows="5"
                            placeholder="Describe tu evento..."
                        ><?php echo htmlspecialchars($event['descripcion']); ?></textarea>
                        <div class="error-txt" id="error-descripcion"></div>
                    </div>
                </section>

                <section class="form-section">
                    <h2 class="section-title">Fecha, horario y capacidad</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha">Fecha del evento</label>
                            <input 
                                type="date" 
                                id="fecha" 
                                name="fecha"
                                value="<?php echo htmlspecialchars($event['fecha']); ?>"
                                class="input-with-icon"
                                required
                            >
                            <div class="error-txt" id="error-fecha"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="hora">Hora de inicio</label>
                            <input 
                                type="time" 
                                id="hora" 
                                name="hora"
                                value="<?php echo htmlspecialchars($event['hora_inicio']); ?>"
                                class="input-with-icon"
                                required
                            >
                            <div class="error-txt" id="error-hora"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group capacity-group">
                            <label for="capacidad">Capacidad total</label>
                            <div class="capacity-control">
                                <input 
                                    type="number" 
                                    id="capacidad" 
                                    name="capacidad"
                                    value="<?php echo htmlspecialchars($event['capacidad_max']); ?>"
                                    min="1"
                                    max="30"
                                    required
                                >
                                <div class="capacity-buttons">
                                    <button type="button" class="capacity-btn" data-action="increment">▲</button>
                                    <button type="button" class="capacity-btn" data-action="decrement">▼</button>
                                </div>
                            </div>
                            <span class="capacity-hint">Máx. 30 personas</span>
                            <div class="error-txt" id="error-capacidad"></div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="visibility-control">
                        <label for="visibilidad">Visibilidad</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilidad" name="estado" 
                                <?php echo ($event['estado'] == 'activo') ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="btn-save" id="btnSaveEdit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; margin-right: 5px;">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                        Guardar cambios
                    </button>
                    <button type="button" class="btn-cancel" onclick="window.location.href='mi_evento.php'">Cancelar</button>
                </div>
            </form>
            <?php endif; ?>
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