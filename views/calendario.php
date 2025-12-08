<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../php/conexion.php'; 

$user_id = $_SESSION['user_id'];
$events_data = [];

try {
    $sql = "
        SELECT 
            e.id_evento,
            e.nombre_evento,
            e.fecha,
            e.hora_inicio,
            e.estado,
            e.capacidad_max,
            c.nombre_categoria,
            (SELECT COALESCE(SUM(r2.cupo), 0) FROM Reservacion r2 WHERE r2.Evento_id_evento = e.id_evento) as total_ocupados,
            r.cupo as mi_cupo
        FROM 
            Reservacion r
        JOIN 
            Evento e ON r.Evento_id_evento = e.id_evento
        JOIN 
            Categoria c ON e.Categoria_id_Categoria = c.id_Categoria
        WHERE 
            r.Usuario_id_usuario = :user_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $status = 'available'; 
        $now = new DateTime();
        $event_date = new DateTime($row['fecha'] . ' ' . $row['hora_inicio']);

        if ($row['estado'] === 'cancelado') {
            $status = 'cancelled';
        } elseif ($event_date < $now) {
            $status = 'passed';
        } elseif ($row['total_ocupados'] >= $row['capacidad_max']) {
            $status = 'full';
        }

        $time_formatted = date('h:i a', strtotime($row['hora_inicio']));
        
        $events_data[] = [
            'id' => $row['id_evento'],
            'title' => $row['nombre_evento'],
            'date' => $row['fecha'], 
            'time' => $time_formatted,
            'category' => $row['nombre_categoria'],
            'attendees' => $row['total_ocupados'],
            'capacity' => $row['capacidad_max'],
            'my_spots' => $row['mi_cupo'],
            'status' => $status
        ];
    }

} catch (PDOException $e) {
    error_log("Error calendario: " . $e->getMessage());
}
?>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->
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
                        <option value="all">Categoría (Todas)</option>
                        <option>Salón para eventos</option>
                        <option>Auditorio</option>
                        <option>Espacio al aire libre</option>
                        <option>Sala de conferencias</option>
                        <option>Boda</option>
                        <option>Concierto</option>
                        <option>Deportes</option>
                        <option>Arte</option>
                    </select>
                </div>
            </div>

            <div class="month-navigation">
                <button class="nav-btn" id="prev-month">
                    <span>‹</span> Mes anterior
                </button>
                <div class="current-month" id="current-month"></div>
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
                <span>Evento disponible (Activo)</span>
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
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->

    <script>
        const events = <?php echo json_encode($events_data); ?>;

        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        function initCalendar() {
            updateMonthDisplay();
            renderCalendar();
        }

        function updateMonthDisplay() {
            const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            document.getElementById('current-month').textContent = `${months[currentMonth]}, ${currentYear}`;
        }

        function renderCalendar() {
            const calendarDays = document.getElementById('calendar-days');
            calendarDays.innerHTML = '';

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            const today = new Date();
            const isCurrentMonth = today.getMonth() === currentMonth && today.getFullYear() === currentYear;

            for (let i = firstDay - 1; i >= 0; i--) {
                const dayCell = createDayCell(daysInPrevMonth - i, true);
                calendarDays.appendChild(dayCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = isCurrentMonth && day === today.getDate();
                const dayCell = createDayCell(day, false, isToday);
                
                const monthStr = String(currentMonth + 1).padStart(2, '0');
                const dayStr = String(day).padStart(2, '0');
                const dateStr = `${currentYear}-${monthStr}-${dayStr}`;
                
                const dayEvents = events.filter(event => event.date === dateStr);
                
                dayEvents.forEach(event => {
                    const eventEl = document.createElement('div');
                    eventEl.className = `event-item ${event.status}`;
                    eventEl.textContent = event.title;
                    eventEl.title = `${event.time} - ${event.category}`; 
                    eventEl.onclick = (e) => {
                        e.stopPropagation();
                        showEventModal(event);
                    };
                    dayCell.appendChild(eventEl);
                });
                
                calendarDays.appendChild(dayCell);
            }

            const totalCells = calendarDays.children.length;
            const remainingCells = 42 - totalCells; 
            if(remainingCells < 7) { 
                for (let day = 1; day <= remainingCells; day++) {
                    const dayCell = createDayCell(day, true);
                    calendarDays.appendChild(dayCell);
                }
            }
        }

        function createDayCell(day, isOtherMonth, isToday = false) {
            const dayCell = document.createElement('div');
            dayCell.className = 'day-cell';
            if (isOtherMonth) dayCell.classList.add('other-month');
            if (isToday) dayCell.classList.add('today');
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = day;
            dayCell.appendChild(dayNumber);
            
            return dayCell;
        }

        function showEventModal(event) {
            const modal = document.getElementById('event-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');
            
            modalTitle.textContent = event.title;
            
            const statusText = {
                available: 'Disponible',
                full: 'Lleno',
                passed: 'Pasado',
                cancelled: 'Cancelado'
            };
            
            modalBody.innerHTML = `
                <div class="event-detail">
                    <strong>Fecha:</strong>
                    ${formatDate(event.date)}
                </div>
                <div class="event-detail">
                    <strong>Hora:</strong>
                    ${event.time}
                </div>
                <div class="event-detail">
                    <strong>Categoría:</strong>
                    ${event.category}
                </div>
                <div class="event-detail">
                    <strong>Asistentes Totales:</strong>
                    ${event.attendees} / ${event.capacity}
                </div>
                <div class="event-detail" style="background-color: #e8f5e9; padding: 5px; border-radius: 4px;">
                    <strong>Tu Reservación:</strong>
                    ${event.my_spots} lugares
                </div>
                <div class="event-detail">
                    <strong>Estado:</strong>
                    ${statusText[event.status] || event.status}
                </div>
            `;
            
            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('event-modal').classList.remove('active');
        }

        function formatDate(dateStr) {
            const [year, month, day] = dateStr.split('-');
            const date = new Date(year, month - 1, day);
            
            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            
            return `${days[date.getDay()]} ${date.getDate()} de ${months[date.getMonth()]}, ${date.getFullYear()}`;
        }

        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateMonthDisplay();
            renderCalendar();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateMonthDisplay();
            renderCalendar();
        });

        document.getElementById('close-modal').addEventListener('click', closeModal);

        document.getElementById('event-modal').addEventListener('click', (e) => {
            if (e.target.id === 'event-modal') {
                closeModal();
            }
        });

        document.getElementById('category-select').addEventListener('change', (e) => {
            const selectedCat = e.target.value;
            if(selectedCat === "Categoría (Todas)" || selectedCat === "all") {
                renderCalendar();
            } else {
                const calendarDays = document.getElementById('calendar-days');
                console.log('Filtrar por:', selectedCat);
            }
        });

        // Iniciar
        initCalendar();
    </script>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------- -->

</body>
</html>