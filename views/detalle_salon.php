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
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/details.css">
    <title>Agora Salón</title>
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
                        <a href="eventos.html">Eventos próximos y pasados</a>
                        <a href="crear_evento.html">Crear un evento</a>
                        <a href="mi_evento.html">Mis eventos</a>
                    </div>
                </li>
                <li class="menu">
                    <button id="btn" class="menu-btn" aria-haspopup="true" aria-expanded="false">
                        Reservaciones ▾
                    </button>
                    <div class="menu-content">
                        <a href="reservar.html">Reservar evento</a>
                        <a href="historial_r.html">Historial de reservaciones</a>
                    </div>
                </li>
                <li><button class="menu-btn"><a href="calendario.html">Calendario</a></button></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <header class="event-header">
            <div class="event-header-content">
                <h1 class="event-name">Salón</h1>
                <p class="event-category">Salón para eventos</p>
            </div>
        </header>

        <main class="event-content">
            <section class="about-section">
                <h2 class="section-title">Sobre el evento</h2>
                <p class="section-text">
                    Celebra tus momentos más importantes en un espacio diseñado para brillar.
                </p>
                <p class="section-text">
                    Nuestro salón de eventos ofrece un ambiente moderno, versátil y totalmente equipado para bodas, conferencias, cumpleaños, graduaciones y cualquier ocasión especial.
                </p>
                <p class="section-text">
                    Disfruta de amplias instalaciones, excelente iluminación y la comodidad que tus invitados merecen.
                </p>
            </section>

            <section class="event-info">
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Horario:</span>
                        <span class="info-value">Martes 4 Nov - 10:00 a.m.</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado del evento:</span>
                        <span class="status-badge active">Activo</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Espacios:</span>
                        <span class="info-value">23 / 30</span>
                    </div>
                </div>
            </section>

            <div class="action-section">
                <button class="btn-reserve"><a href="../views/reservar.html">Reservar lugares</a></button>
            </div>
        </main>
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