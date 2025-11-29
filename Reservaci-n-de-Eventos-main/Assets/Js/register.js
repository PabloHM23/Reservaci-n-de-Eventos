document.addEventListener('DOMContentLoaded', () => {
    const formRegistro = document.getElementById('formRegistro');
    if (!formRegistro) return;

    formRegistro.addEventListener('submit', async (e) => {
        e.preventDefault();
        limpiarErrores();

        const datos = {
            nombre: document.getElementById('nombre').value.trim(),
            email: document.getElementById('email-reg').value.trim(),
            password: document.getElementById('pass-reg').value.trim() 
        };

        // Validación básica
        if (datos.nombre.length < 3) {
            mostrarError('nombre', 'El nombre debe tener al menos 3 caracteres');
            return;
        }

        if (!validarEmail(datos.email)) {
            mostrarError('email-reg', 'Ingresa un correo electrónico válido');
            return;
        }

        if (datos.password.length < 6) {
            mostrarError('pass-reg', 'La contraseña debe tener al menos 6 caracteres');
            return;
        }

        // Mostrar loading
        const btn = formRegistro.querySelector('button[type="submit"]');
        btn.disabled = true;
        const textoOriginal = btn.textContent;
        btn.textContent = 'Registrando...';

        try {
            const response = await fetch('php/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            const data = await response.json();

            if (data.success) {
                alert('¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.');
                formRegistro.reset();
                
                // Cambiar a vista de login para que el usuario inicie sesión
                document.getElementById('vistaRegistro').style.display = 'none';
                document.getElementById('vistaLogin').style.display = 'block';
            } else {
                alert('Error: ' + data.message);
            }

        } catch (error) {
            alert('Error de conexión. Por favor intenta de nuevo.');
        } finally {
            btn.disabled = false;
            btn.textContent = textoOriginal;
        }
    });
});

// Función para validar email
const validarEmail = email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

function mostrarError(inputId, mensaje) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    let errorDiv = input.parentElement.querySelector('.error-txt');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-txt';
        input.parentElement.appendChild(errorDiv);
    }

    errorDiv.textContent = mensaje;
    errorDiv.style.cssText = 'color: red; font-size: 12px; margin-top: 5px;';
    input.style.borderColor = 'red';
}

// Función para limpiar errores
function limpiarErrores() {
    document.querySelectorAll('.error-txt').forEach(error => error.textContent = '');
    document.querySelectorAll('#formRegistro input').forEach(input => input.style.borderColor = '');
}