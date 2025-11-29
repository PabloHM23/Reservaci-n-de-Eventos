php<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/nav_footer.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_welcom.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/home_styles/home_events.css">
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/events.css">
    <script src="../Assets/Js/home.js"></script>
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/calendario.css">
    <title>Agora Calendario</title>
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
        <div class="calendar-wrapper">
            <div class="calendar-header">
                <h1>Calendario</h1>
                <div class="category-filter">
                    <select id="category-select">
                        <option>Categoría (Todas)</option>
                        <option>Salón para eventos</option>
                        <option>Auditorio</option>
                        <option>Espacio al aire libre</option>
                        <option>Sala de conferencias</option>
                    </select>
                </div>
            </div>

            <div class="month-navigation">
                <button class="nav-btn" id="prev-month">
                    <span>‹</span> Mes anterior
                </button>
                <div class="current-month" id="current-month">Nov, 2025</div>
                <button class="nav-btn" id="next-month">
                    Mes siguiente <span>›</span>
                </button>
            </div>

            <div class="calendar-grid">
                <div class="weekday-header">Domingo</div>
                <div class="weekday-header">Lunes</div>
                <div class="weekday-header">Martes</div>
                <div class="weekday-header">Miércoles</div>
                <div class="weekday-header">Jueves</div>
                <div class="weekday-header">Viernes</div>
                <div class="weekday-header">Sábado</div>

                <div id="calendar-days"></div>
            </div>
        </div>

        <aside class="legend">
            <h3>Leyenda</h3>
            <div class="legend-item">
                <span class="legend-color available"></span>
                <span>Evento disponible</span>
            </div>
            <div class="legend-item">
                <span class="legend-color full"></span>
                <span>Evento lleno</span>
            </div>
            <div class="legend-item">
                <span class="legend-color passed"></span>
                <span>Evento pasado</span>
            </div>
            <div class="legend-item">
                <span class="legend-color cancelled"></span>
                <span>Evento cancelado</span>
            </div>
        </aside>
    </div>

    <div id="event-modal" class="modal">
        <div class="modal-content">
            <button class="modal-close" id="close-modal">&times;</button>
            <h2 id="modal-title"></h2>
            <div id="modal-body"></div>
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

    <script src="../Assets/Js/calendario.js"></script>
</body>
</html>