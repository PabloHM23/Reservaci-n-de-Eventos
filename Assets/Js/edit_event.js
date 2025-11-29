document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('editEventForm');
    if (!form) return;

    const eventId = form.getAttribute('data-event-id');
    const btnSaveEdit = document.getElementById('btnSaveEdit');

    const clearErrors = () => {
        document.querySelectorAll('.error-txt').forEach(div => div.textContent = '');
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


    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const data = {
            event_id: eventId,
            nombre: document.getElementById('nombre').value.trim(),
            categoria: document.getElementById('categoria').value,
            descripcion: document.getElementById('descripcion').value.trim(),
            fecha: document.getElementById('fecha').value,
            hora_inicio: document.getElementById('hora').value,
            capacidad: parseInt(document.getElementById('capacidad').value),
            estado: document.getElementById('visibilidad').checked ? 'activo' : 'cancelado'
        };

        let hasError = false;

        if (data.nombre.length < 5 || data.nombre.length > 33) {
            setError('nombre', 'El nombre debe tener entre 5 y 33 caracteres.');
            hasError = true;
        }
        if (data.categoria === "" || isNaN(parseInt(data.categoria))) {
            setError('categoria', 'Debes seleccionar una categoría válida.');
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

        btnSaveEdit.disabled = true;
        const originalText = btnSaveEdit.innerHTML;
        btnSaveEdit.innerHTML = `Guardando...`;

        try {
            const response = await fetch('../php/update_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = 'mi_evento.php'; 
            } else {
                alert('Fallo al actualizar evento: ' + result.message);
            }

        } catch (error) {
            console.error('Error de red o servidor:', error);
            alert('Hubo un error de conexión al intentar actualizar el evento.');
        } finally {
            btnSaveEdit.disabled = false;
            btnSaveEdit.innerHTML = originalText;
        }
    });
    
    document.querySelectorAll('.capacity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = document.getElementById('capacidad');
            let currentValue = parseInt(input.value);
            const action = this.getAttribute('data-action');
            
            if (isNaN(currentValue)) currentValue = 0;
            
            if (action === 'increment' && currentValue < 30) {
                input.value = currentValue + 1;
            } else if (action === 'decrement' && currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

});