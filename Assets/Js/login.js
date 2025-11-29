document.addEventListener('DOMContentLoaded', () => {
    const formLogin = document.getElementById('formLogin');
    if (!formLogin) return;

    formLogin.addEventListener('submit', async (e) => {
        e.preventDefault();
        limpiarErroresLogin();

        const datos = {
            email: document.getElementById('email-login').value.trim(),
            password: document.getElementById('pass-login').value.trim()
        };

        // Validación básica:
        if (!validarEmail(datos.email)) {
            mostrarErrorLogin('email-login', 'Ingresa un correo válido');
            return;
        }

        if (!datos.password) {
            mostrarErrorLogin('pass-login', 'La contraseña es obligatoria');
            return;
        }

        // Mostrar loading:
        const btn = formLogin.querySelector('button[type="submit"]');
        btn.disabled = true;
        const textoOriginal = btn.textContent;
        btn.textContent = 'Iniciando...';

        try {
            const response = await fetch('php/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            const data = await response.json();

            if (data.success) {
                alert(`¡Bienvenido ${data.user.nombre}!`);
                cerrarModal();
                actualizarUIUsuario(data.user);
            } else {
                alert('Error: ' + data.message);
            }

        } catch (error) {
            alert('Error de conexión');
        } finally {
            btn.disabled = false;
            btn.textContent = textoOriginal;
        }
    });
});

function mostrarErrorLogin(inputId, mensaje) {
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

function limpiarErroresLogin() {
    const formLogin = document.getElementById('formLogin');
    if (!formLogin) return;
    
    formLogin.querySelectorAll('.error-txt').forEach(error => error.textContent = '');
    formLogin.querySelectorAll('input').forEach(input => input.style.borderColor = '');
}

function cerrarModal() {
    const modal = document.getElementById('miModal');
    if (modal) modal.style.display = 'none';
}

function actualizarUIUsuario(user) {
    const btnLogin = document.querySelector('#abrirModalBtn');
    const btnRegistrarse = document.getElementById('btn_registrarse');
    
function actualizarUIUsuario(user) {

    window.location.reload();
}

    if (btnLogin) btnLogin.style.display = 'none';
    if (btnRegistrarse) btnRegistrarse.style.display = 'none';
    
    const navMenus = document.querySelector('.nav-menus');
    if (navMenus) {
        const userMenu = document.createElement('li');
        userMenu.innerHTML = `<button class="menu-btn">Hola, ${user.nombre}</button>`;
        navMenus.appendChild(userMenu);
    }

    
}