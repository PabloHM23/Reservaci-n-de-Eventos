<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
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
    <script src="../Assets/Js/home.js"></script>
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/perfil.css">
    <title>Perfil</title>
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
                <li>
                    <button class="menu-btn user-welcome">
                        Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </button>
                </li>
                <li>
                </li>
            </ul>
        </nav>
    </div>
    <br>
    <div class="container">
        <aside class="profile-sidebar">
            <div class="profile-card">
                <div class="avatar">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                
                <button class="btn-logout" onclick="logout()">Cerrar sesión</button>
                
                <div class="profile-info">
                    <div class="info-group">
                        <label>Nombre:</label>
                        <p><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    </div>
                    
                    <div class="info-group">
                        <label>Correo electrónico:</label>
                        <p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                    </div>
                </div>
                
                <button class="btn-edit"><a href="edit_info.php">Editar información</a></button>
            </div>
        </aside>

        <main class="main-content">
            <h1>Perfil</h1>
            <div class="reservations-card">
                <div class="header-row">
                    <h2>Reservaciones</h2>
                    <h2>Espacios reservados</h2>
                </div>
                
                <div class="empty-state">
                    <p>No tienes reservaciones actualmente</p>
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

    <script>
    function logout() {
        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
            window.location.href = '../php/logout.php';
        }
    }
    </script>
</body>
</html>