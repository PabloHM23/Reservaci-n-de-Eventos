document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reservationForm');
    if (!form) return;

    const eventId = form.getAttribute('data-event-id');
    const espaciosInput = document.getElementById('espacios');
    const btnReservar = document.getElementById('btnReservar');
    
    const maxReservable = parseInt(form.getAttribute('data-max-reservable'));
    const currentSlots = parseInt(espaciosInput.getAttribute('data-current-slots'));
    const maxCapacity = parseInt(espaciosInput.max);

    if (isNaN(maxReservable) || maxReservable === 0) {
        btnReservar.disabled = true;
        btnReservar.textContent = "Agotado";
    }

    document.querySelectorAll('.control-btn').forEach(button => {
        button.addEventListener('click', function() {
            let currentValue = parseInt(espaciosInput.value);
            const action = this.getAttribute('data-action');
            
            if (isNaN(currentValue)) currentValue = 0;
            
            if (action === 'increment' && currentValue < maxReservable) {
                espaciosInput.value = currentValue + 1;
            } else if (action === 'decrement' && currentValue > 1) {
                espaciosInput.value = currentValue - 1;
            }
            
            espaciosInput.dispatchEvent(new Event('change'));
        });
    });
    
    espaciosInput.addEventListener('input', function() {
        let value = parseInt(this.value);
        if (isNaN(value)) {
            value = 1;
        }
        if (value < 1) {
            this.value = 1;
        } else if (value > maxReservable) {
            this.value = maxReservable;
            alert(`No puedes reservar más de ${maxReservable} cupos.`);
        }
        
        const isNewReservation = currentSlots === 0;
        const newText = (value > 0) ? 
                        (isNewReservation ? 'Reservar lugares' : 'Actualizar reserva') : 
                        'Reservar lugares';
        btnReservar.textContent = newText;
        btnReservar.disabled = (value === 0 || value > maxCapacity);
    });
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const espacios = parseInt(espaciosInput.value);

        if (isNaN(espacios) || espacios < 1 || espacios > maxReservable) {
            alert('La cantidad de espacios es inválida.');
            return;
        }

        btnReservar.disabled = true;
        const originalText = btnReservar.textContent;
        btnReservar.textContent = 'Procesando...';

        const dataToSend = {
            event_id: eventId,
            espacios: espacios,
            current_slots: currentSlots
        };

        try {
            const response = await fetch('../php/make_reservation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                alert('¡Éxito! ' + result.message);
                window.location.href = 'historial_r.php'; 
            } else {
                alert('Fallo en la reserva: ' + result.message);
            }

        } catch (error) {
            console.error('Error de red o servidor:', error);
            alert('Hubo un error de conexión al procesar la reserva.');
        } finally {
            btnReservar.disabled = false;
            btnReservar.textContent = originalText;
        }
    });

});