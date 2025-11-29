document.addEventListener('DOMContentLoaded', function() {

    const fechaEventoInput = flatpickr("#fecha-evento", {
        dateFormat: "d/m/Y",
        placeholder: "dd/mm/aaaa",
        allowInput: false
    });

    const horaInicioInput = flatpickr("#hora-inicio", {
        enableTime: true,  
        noCalendar: true,    
        dateFormat: "h:i K",
        defaultHour: 18,     
        defaultMinute: 30,
        placeholder: "06:30 p.m.",
        allowInput: false
    });

    const convertDateToSqlFormat = (dateStr) => {
        if (!dateStr) return null;
        const [day, month, year] = dateStr.split('/');
        return `${year}-${month}-${day}`;
    };

    const convertTimeToSqlFormat = (timeStr) => {
        if (!timeStr) return null;
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');

        if (hours === '12') {
            hours = '00';
        }
        if (modifier && modifier.toUpperCase() === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }

        return `${hours.toString().padStart(2, '0')}:${minutes}`;
    };

    const setError = (id, message) => {
        const errorDiv = document.getElementById(`error-${id}`);
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.color = 'red';
            errorDiv.style.fontSize = '0.8rem';
            return true;
        }
        return false;
    };

    const clearErrors = () => {
        document.querySelectorAll('.error-txt').forEach(div => div.textContent = '');
    };

    const form = document.getElementById('createEventForm');
    const btnPublicar = document.getElementById('btnPublicar');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const data = {
            nombre: document.getElementById('nombre').value.trim(),
            categoria: document.getElementById('categoria').value,
            descripcion: document.getElementById('descripcion').value.trim(),
            fecha: document.getElementById('fecha-evento').value,
            hora_inicio: document.getElementById('hora-inicio').value,
            capacidad: parseInt(document.getElementById('capacidad').value)
        };

        let hasError = false;

        if (data.nombre.length < 5 || data.nombre.length > 33) {
            setError('nombre', 'El nombre debe tener entre 5 y 33 caracteres.');
            hasError = true;
        }
        if (data.categoria === "" || data.categoria === "Selecciona una categoría") {
            setError('categoria', 'Debes seleccionar una categoría.');
            hasError = true;
        }
        if (data.fecha === "") {
            setError('fecha', 'La fecha del evento es obligatoria.');
            hasError = true;
        }
        if (data.hora_inicio === "") {
            setError('hora', 'La hora de inicio es obligatoria.');
            hasError = true;
        }
        if (isNaN(data.capacidad) || data.capacidad <= 0 || data.capacidad > 30) {
            setError('capacidad', 'La capacidad debe ser un número entre 1 y 30.');
            hasError = true;
        }
        
        if (hasError) {
            alert('Por favor, corrige los errores en el formulario.');
            return;
        }

        const sqlDate = convertDateToSqlFormat(data.fecha);
        const sqlTime = convertTimeToSqlFormat(data.hora_inicio);

        const dataToSend = {
            nombre: data.nombre,
            categoria: data.categoria,
            descripcion: data.descripcion,
            fecha: sqlDate,
            hora_inicio: sqlTime,
            capacidad: data.capacidad
        };

        btnPublicar.disabled = true;
        const originalText = btnPublicar.innerHTML;
        btnPublicar.innerHTML = `Publicando...`;

        try {
            const response = await fetch('../php/create_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = 'mi_evento.php'; 
            } else {
                alert('Fallo al publicar evento: ' + result.message);
            }

        } catch (error) {
            console.error('Error de red o servidor:', error);
            alert('Hubo un error de conexión al intentar publicar el evento.');
        } finally {
            btnPublicar.disabled = false;
            btnPublicar.innerHTML = originalText;
        }
    });

});