<?php
session_start();

require_once 'php/conexion.php'; 

$events = [];
$error_message = '';

function format_event_date_time($date_str, $time_str) {
    if (!$date_str) return '';
    setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');
    $timestamp = strtotime($date_str);
    $date_formatted = strftime('%A, %d de %B de %Y', $timestamp);
    
    $time_12h = date('h:i a', strtotime($time_str));
    
    return $date_formatted . ' a las ' . strtolower($time_12h);
}

try {
    $sql = "
        SELECT 
            e.id_evento,
            e.nombre_evento,
            e.descripcion,
            e.fecha,
            e.hora_inicio,
            e.capacidad_max,
            c.nombre_categoria,
            COALESCE(SUM(r.cupo), 0) AS cupos_ocupados
        FROM 
            Evento e
        JOIN 
            Categoria c ON e.Categoria_id_Categoria = c.id_Categoria
        LEFT JOIN 
            Reservacion r ON e.id_evento = r.Evento_id_evento
        WHERE
            e.estado = 'activo' AND e.fecha >= CURDATE()
        GROUP BY 
            e.id_evento
        ORDER BY 
            e.fecha ASC, e.hora_inicio ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error al cargar los eventos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Assets/Styles/home_styles/nav_footer.css">
    <link rel="stylesheet" type="text/css" href="Assets/Styles/home_styles/home_welcom.css">
    <link rel="stylesheet" type="text/css" href="Assets/Styles/home_styles/home_events.css">
    <link rel="stylesheet" type="text/css" href="Assets/Styles/home_styles/login.css">
    <script src="Assets/Js/home.js"></script>
    <script src="Assets/Js/register.js"></script>
    <script src="Assets/Js/login.js"></script>
    <title>Agora</title>
    <link rel="shortcut icon" href="Assets/img/logo.png" type="image/x-icon">
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Hoja de estilo de los eventos: -->
     <style>
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 20px;
            padding: 50px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .event-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .card-image-placeholder {
            height: 180px;
            background-color: #4a5f8f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: bold;
        }
        .card-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-grow: 1;
        }
        .card-category {
            font-size: 0.85rem;
            color: #4a5f8f;
            font-weight: 600;
        }
        .card-title {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }
        .card-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
            flex-grow: 1;
        }
        .card-footer {
            border-top: 2px solid #eee;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-date-time {
            font-size: 0.85rem;
            color: #555;
        }
        .card-capacity {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .card-capacity.full {
             color: #d32f2f;
        }
        .card-capacity.available {
             color: #388e3c;
        }
        .card-button {
            background-color: #4a5f8f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s;
            display: block;
        }
        .card-button:hover {
            background-color: #3e5178;
        }
        .no-events-message {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .ver-mas-btn {
            text-align: center;
            margin: 30px 0;
        }
        .ver-mas-btn a {
            background-color: #4a5f8f;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .ver-mas-btn a:hover {
            background-color: #3e5178;
        }

        @media (max-width: 600px) {
            .events-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }
        }
    </style>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
</head>
<body>
    <div class="nav">
        <div class="logo"><a href="index.php"><img src="Assets/img/title.png" alt=""></a></div>
        <nav class="nav_links">
            <ul class="nav-menus">
                <li class="menu">
                    <button id="btn" class="menu-btn" aria-haspopup="true" aria-expanded="false">
                        Eventos ▾
                    </button>
                    <div class="menu-content">
                        <a href="views/eventos.php">Eventos próximos y pasados</a>
                        <a href="views/crear_evento.php">Crear un evento</a>
                        <a href="views/mi_evento.php">Mis eventos</a>
                    </div>
                </li>
                <li class="menu">
                    <button id="btn" class="menu-btn" aria-haspopup="true" aria-expanded="false">
                        Reservaciones ▾
                    </button>
                    <div class="menu-content">
                        <a href="views/reservar.php">Reservar evento</a>
                        <a href="views/historial_r.php">Historial de reservaciones</a>
                    </div>
                </li>
                <li><button class="menu-btn"><a href="views/calendario.php">Calendario</a></button></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                                                <a href="views/perfil.php" class="user-welcome-link">
                            <button class="menu-btn user-welcome">
                                Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </button>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <button id="abrirModalBtn" class="btn menu-btn">Iniciar sesión</button>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <button id="btn_registrarse" class="btn-registrarse">Registrarse</button>
            <?php endif; ?>
            
            <div id="miModal" class="modal">
                <div class="modal-contenido">
                    <div id="vistaRegistro">
                        <span class="cerrar">&times;</span>
                            <h3>Regístrate con nosotros</h3>
                            <form id="formRegistro" novalidate>
                             
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" class="Sololetras" name="nombre"><br>
                                <div class="error-txt"></div><label for="email-reg">Correo</label>
                                <input type="email" id="email-reg" name="email" placeholder="Ejem: correo@gmail.com">
                                <div class="error-txt"></div><label for="pass-reg">Contraseña</label>
                                
                                <div class="password-wrapper">
                                <input type="password" id="pass-reg" name="pass-reg">
                                <!-- <img src="Assets/img/pass2.png" class="pass_icon"> -->
                                </div>
                                <div class="error-txt"></div> <button type="submit" class="btn btn-primario">Crear cuenta</button>
                            </form>   
                        <div class="toggle-seccion">
                            <span>¿Ya tienes cuenta?</span>
                            <button id="mostrarLoginBtn" class="btn btn-secundario">Iniciar sesión</button>
                        </div>
                    </div>

                    <div id="vistaLogin" style="display: none;">
                        <span class="cerrar">&times;</span>
                        <h3>Iniciar sesión</h3>
                        
                        <form id="formLogin" novalidate>
                            <label for="email-login">Correo</label>
                            <input class="mail" type="email" id="email-login" name="email" placeholder="Ejem: correo@gmail.com">
                            <div class="error-txt"></div> <label for="pass-login">Contraseña</label> 
                            <div class="password-wrapper"> <input type="password" id="pass-login" name="pass-login"> 
                                <!-- <img src="Assets/img/pass2.png" class="pass_icon"> -->
                            </div> 
                            <div class="error-txt"></div> <button type="submit" class="btn btn-primario">Entrar</button>
                        </form>
                        
                        <div class="toggle-seccion">
                            <span>¿No tienes cuenta?</span>
                            <button id="mostrarRegistroBtn" class="btn btn-secundario">Regístrate</button>
                        </div>
                    </div>
                </div>
            </div>  
        </nav>
    </div>

    <section class="info_main">
        <div class="info">
            <div class="cont_img">
                <img src="Assets/img/img_home.png">

                <div class="info_img">
                    <h1>Tu Escenario, Tu Evento.</h1>
                    <p>Encuentra, crea y gestiona eventos que inspiran.</p>
                    <ul>
                        <li>Descubre y asiste: Conciertos, conferencias, bodas y más.</li>
                        <li>Crea en minutos: Arma tu propio evento fácilmente.</li>
                        <li>Gestión amigable: Gestiona y administra tus eventos.</li>
                    </ul>
                    <div class="buttons">
                        <button class="button_1"><a href="views/crear_evento.php">Empieza a crear tu evento ahora</a></button>
                        <button class="button_2"><a href="views/eventos.php">Explorar Eventos</a></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>

    <section class="info_main_2">
        <h2>Póximos Eventos Destacados</h2>
        <p>¡No te pierdas de los mejores eventos cerca de ti!</p>
    </section>
    
    <br>
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Cargar eventos que existan: -->
    <section class="event-cards-section">
        <?php if ($error_message): ?>
            <div class="error-message" style="text-align: center; padding: 20px; color: red;">
                Error: <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($events)): ?>
            <div class="no-events-message">
                <p>No hay eventos próximos disponibles para reservar.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $event): 
                    $cupos_disponibles = $event['capacidad_max'] - $event['cupos_ocupados'];
                    $es_lleno = $cupos_disponibles <= 0;
                    $capacidad_clase = $es_lleno ? 'full' : 'available';
                ?>
                <div class="event-card">
                    <div class="card-image-placeholder">
                        <?php echo htmlspecialchars(substr($event['nombre_categoria'], 0, 1)); ?>
                    </div>
                    <div class="card-content">
                        <p class="card-category"><?php echo htmlspecialchars($event['nombre_categoria']); ?></p>
                        <h3 class="card-title"><?php echo htmlspecialchars($event['nombre_evento']); ?></h3>
                        <p class="card-description"><?php echo htmlspecialchars($event['descripcion'] ?? 'Evento sin descripción detallada.'); ?></p>
                        
                        <div class="card-footer">
                            <p class="card-date-time">
                                <?php echo format_event_date_time($event['fecha'], $event['hora_inicio']); ?>
                            </p>
                            <p class="card-capacity <?php echo $capacidad_clase; ?>">
                                Cupos: <?php echo $event['cupos_ocupados'] . ' / ' . $event['capacidad_max']; ?>
                            </p>
                        </div>
                        
                        <a 
                            href="views/reservar.php?event_id=<?php echo $event['id_evento']; ?>" 
                            class="card-button"
                            <?php if ($es_lleno): ?> style="opacity: 0.6; cursor: not-allowed; pointer-events: none;" <?php endif; ?>
                        >
                            <?php echo $es_lleno ? 'Agotado' : '¡Reservar ahora!'; ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="ver-mas-btn">
                <a href="views/eventos.php">Ver todos los eventos →</a>
            </div>
        <?php endif; ?>
    </section>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <br>

    <div id="footer">
        <footer class="footer">
            <div class="footer_content">
                <div class="name">
                    <img src="Assets/img/title.png">
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