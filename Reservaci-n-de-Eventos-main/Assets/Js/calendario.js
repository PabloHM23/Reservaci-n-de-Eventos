const events = [
    {
        id: 1,
        title: "Concierto benéfico",
        date: "2025-11-27",
        time: "05:00 p.m",
        category: "Salón para eventos",
        attendees: 23,
        capacity: 30,
        status: "available"
    },
    {
        id: 2,
        title: "Conferencia de tecnología",
        date: "2025-11-27",
        time: "02:00 p.m",
        category: "Auditorio",
        attendees: 150,
        capacity: 150,
        status: "full"
    },
    {
        id: 3,
        title: "Taller de cocina",
        date: "2025-11-15",
        time: "10:00 a.m",
        category: "Sala de conferencias",
        attendees: 12,
        capacity: 20,
        status: "passed"
    },
    {
        id: 4,
        title: "Fiesta de fin de año",
        date: "2025-12-31",
        time: "08:00 p.m",
        category: "Salón para eventos",
        attendees: 45,
        capacity: 100,
        status: "available"
    },
    {
        id: 5,
        title: "Evento cancelado",
        date: "2025-11-20",
        time: "03:00 p.m",
        category: "Espacio al aire libre",
        attendees: 0,
        capacity: 50,
        status: "cancelled"
    }
];

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
        
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(event => event.date === dateStr);
        
        dayEvents.forEach(event => {
            const eventEl = document.createElement('div');
            eventEl.className = `event-item ${event.status}`;
            eventEl.textContent = event.title;
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
    for (let day = 1; day <= remainingCells; day++) {
        const dayCell = createDayCell(day, true);
        calendarDays.appendChild(dayCell);
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
            <strong>Asistentes:</strong>
            ${event.attendees} / ${event.capacity}
        </div>
        <div class="event-detail">
            <strong>Estado:</strong>
            ${statusText[event.status]}
        </div>
    `;
    
    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('event-modal').classList.remove('active');
}

function formatDate(dateStr) {
    const date = new Date(dateStr + 'T00:00:00');
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
    console.log('Filtrar por:', e.target.value);
});

initCalendar();