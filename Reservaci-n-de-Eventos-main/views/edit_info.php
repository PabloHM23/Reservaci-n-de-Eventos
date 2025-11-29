<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Redirige si no está logueado
    exit;
}
require_once '../php/conexion.php'; 

$user_id = $_SESSION['user_id'];
$user_name = '';
$user_email = '';
$error_message = '';

try {
    $stmt = $pdo->prepare("SELECT nombre, correo_electronico FROM Usuario WHERE id_usuario = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        $user_name = htmlspecialchars($user_data['nombre']);
        $user_email = htmlspecialchars($user_data['correo_electronico']);
    } else {
        // Esto no debería pasar si el user_id es válido
        $error_message = "Error: Usuario no encontrado.";
    }
} catch (PDOException $e) {
    $error_message = "Error al conectar con la base de datos: " . $e->getMessage();
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
    <script src="../Assets/Js/register.js"></script>
    <script src="../Assets/Js/login.js"></script>
    <link rel="stylesheet" type="text/css" href="../Assets/Styles/styles_events/edit_perfil.css">
    <script src="../Assets/Js/edit_info.js"></script> 
    <title>Editar Perfil</title>
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
    <h1>Actualiza tu Información</h1>

    <div class="card">
        <div class="avatar-container">
            <svg class="avatar-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="12" fill="white"/>
                <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#555"/>
            </svg>
        </div>
        <div class="car_info">
            <?php if ($error_message): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <form id="formEditInfo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $user_name; ?>" required><br>
                
                <div class="error-txt" id="error-nombre"></div>
                
                <label for="email-reg">Correo</label>
                <input type="email" id="email-reg" name="email" value="<?php echo $user_email; ?>" required>
                
                <div class="error-txt" id="error-email"></div>
                
                <label for="pass-reg">Contraseña (Dejar vacío para no cambiar)</label>
                <div class="password-wrapper">
                    <input type="password" id="pass-reg" name="new_password"> 
                </div>
                
                <div class="error-txt" id="error-pass"></div>

                <button type="submit" class="btn-save" id="btnGuardar">Guardar Cambios</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='perfil.php'">Cancelar</button>
            </form>
        </div>
    </div>
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