<?php
session_start();
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
                                <input type="text" id="nombre" name="nombre"><br>
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
    
    <section class="event-cards-section">
        <div class="cards-grid">
            <div class="event-card">
                <img src="Assets/img/tag_salon.png" alt="Salón de eventos vacío con mesas puestas">
                <div class="card-overlay">
                    <h3 class="card-title">Salónes</h3>
                    <p class="card-meta">Salón para eventos</p>
                    <p class="card-date">Martes 4 Nov</p>
                    <button class="btn-details purple"><a href="views/detalle_salon.php">Ver Detalles</a></button>
                </div>
            </div>

            <div class="event-card">
                <img src="Assets/img/tag_boda.png" alt="Pareja de novios de espaldas">
                <div class="card-overlay">
                    <h3 class="card-title">Bodas</h3>
                    <p class="card-meta">Boda</p>
                    <p class="card-date">Martes 4 Nov</p>
                    <button class="btn-details purple"><a href="views/detalle_boda.php">Ver Detalles</a></button>
                </div>
            </div>

            <div class="event-card">
                <img src="Assets/img/tag_conf.png" alt="Conferencia de negocios con un panel de personas">
                <div class="card-overlay">
                    <h3 class="card-title">Conferencias</h3>
                    <p class="card-meta">Conferencia</p>
                    <p class="card-date">Miércoles 5 Nov</p>
                    <button class="btn-details purple"><a href="views/detalle_conferencia.php">Ver Detalles</a></button>
                </div>
            </div>

            <div class="event-card">
                <img src="Assets/img/tag_sport.png" alt="Arco de salida de carrera atlética">
                <div class="card-overlay">
                    <h3 class="card-title">Evento Deportivo</h3>
                    <p class="card-meta">Carrera atlética</p>
                    <p class="card-date">Miércoles 5 Nov</p>
                    <button class="btn-details bright-blue"><a href="views/detalle_deporte.php">Ver Detalles</a></button>
                </div>
            </div>

            <div class="event-card">
                <img src="Assets/img/tag_concert.png" alt="Concierto de música con luces de colores">
                <div class="card-overlay">
                    <h3 class="card-title">Conciertos</h3>
                    <p class="card-meta">Concierto</p>
                    <p class="card-date">Jueves 6 Nov</p>
                    <button class="btn-details orange"><a href="views/detalle_concierto.php">Ver Detalles</a></button>
                </div>
            </div>

            <div class="event-card">
                <img src="Assets/img/tag_art.png" alt="Galería de arte con cuadros en la pared">
                <div class="card-overlay">
                    <h3 class="card-title">Expo artes y dibujos</h3>
                    <p class="card-meta">Exposición de arte</p>
                    <p class="card-date">Jueves 6 Nov</p>
                    <button class="btn-details maroon"><a href="views/detalle_arte.php">Ver Detalles</a></button>
                </div>
            </div>
        </div>

        <div class="pagination">
            <span class="pagination-link">&lt; Anterior</span>
            <span class="pagination-link current">1</span>
            <span class="pagination-link">2</span>
            <span class="pagination-link">Siguiente &gt;</span>
        </div>
    </section>

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