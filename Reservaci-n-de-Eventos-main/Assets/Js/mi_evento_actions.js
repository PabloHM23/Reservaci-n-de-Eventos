document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.actions-dropdown').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const content = this.nextElementSibling;
            
            document.querySelectorAll('.actions-dropdown.active').forEach(openBtn => {
                if (openBtn !== this) {
                    openBtn.classList.remove('active');
                }
            });
            this.classList.toggle('active');
        });
    });

    window.addEventListener('click', function() {
        document.querySelectorAll('.actions-dropdown.active').forEach(button => {
            button.classList.remove('active');
        });
    });

    document.querySelectorAll('.action-delete').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation(); 

            const eventId = this.getAttribute('data-event-id');
            const confirmDelete = window.confirm('¿Estás seguro de que quieres eliminar este evento? Esta acción es irreversible.');

            if (!confirmDelete) {
                return;
            }
            
            try {
                const response = await fetch('../php/delete_event.php', {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ event_id: eventId })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    window.location.reload(); 
                } else {
                    alert('Fallo al eliminar el evento: ' + result.message);
                }

            } catch (error) {
                console.error('Error al intentar eliminar:', error);
                alert('Error de conexión o servidor al eliminar el evento.');
            }
        });
    });

    document.querySelectorAll('.toggle-switch input').forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const eventId = this.getAttribute('data-event-id');
            const newState = this.checked ? 'activo' : 'cancelado'; 
            
            console.log(`Intentando cambiar evento ${eventId} a estado: ${newState}`);

        });
    });
});