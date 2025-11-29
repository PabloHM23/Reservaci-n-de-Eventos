document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEditInfo');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Limpiar errores previos
        document.querySelectorAll('.error-txt').forEach(div => div.textContent = '');

        const nombre = document.getElementById('nombre').value.trim();
        const email = document.getElementById('email-reg').value.trim();
        const newPassword = document.getElementById('pass-reg').value.trim();
        
        let hasError = false;

        if (nombre.length < 3) {
            document.getElementById('error-nombre').textContent = 'El nombre debe tener al menos 3 caracteres.';
            hasError = true;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            document.getElementById('error-email').textContent = 'Ingresa un correo electrónico válido.';
            hasError = true;
        }
        if (newPassword && newPassword.length < 6) {
            document.getElementById('error-pass').textContent = 'La contraseña debe tener al menos 6 caracteres.';
            hasError = true;
        }

        if (hasError) {
            return;
        }

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        const originalText = btnGuardar.textContent;
        btnGuardar.textContent = 'Guardando...';

        const dataToSend = {
            nombre: nombre,
            email: email,
            new_password: newPassword
        };

        try {
            const response = await fetch('../php/update_profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });

            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                window.location.href = 'perfil.php'; 
            } else {
                alert('Error al guardar: ' + data.message);
            }

        } catch (error) {
            alert('Error de conexión con el servidor.');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.textContent = originalText;
        }
    });
});