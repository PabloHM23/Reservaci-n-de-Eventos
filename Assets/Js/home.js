document.addEventListener("DOMContentLoaded", function() {

    const allmenuBtns = document.querySelectorAll(".menu-btn");
    allmenuBtns.forEach(function(btn) {
        btn.onclick = function(e) {
            e.stopPropagation();
            
            document.querySelectorAll(".menu-content.show").forEach(function(openContent) {
                if (openContent !== this.nextElementSibling) {
                    openContent.classList.remove("show");
                    openContent.previousElementSibling.setAttribute("aria-expanded", false);
                }
            }.bind(this));


            const content = this.nextElementSibling;
            if (content && content.classList) { 
                const isExpanded = content.classList.toggle("show");
                this.setAttribute("aria-expanded", isExpanded);
            }
        };
    });

    var modal = document.getElementById("miModal");
    var btnAbrirModal = document.getElementById("abrirModalBtn");
    var btnAbrirRegistro = document.getElementById("btn_registrarse");
    var spanCerrar = document.getElementsByClassName("cerrar");

    if (btnAbrirModal) {
        btnAbrirModal.onclick = function() {
            modal.style.display = "flex";
            vistaRegistro.style.display = "none"; 
            vistaLogin.style.display = "block";
        }
    }

    if (btnAbrirRegistro) {
    btnAbrirRegistro.onclick = function() {
        modal.style.display = "flex";
        vistaLogin.style.display = "none"; 
        vistaRegistro.style.display = "block";
    }
}

    for (let i = 0; i < spanCerrar.length; i++) {
        spanCerrar[i].onclick = function() {
            modal.style.display = "none";
        }
    }

    var vistaRegistro = document.getElementById("vistaRegistro");
    var vistaLogin = document.getElementById("vistaLogin");
    var mostrarLoginBtn = document.getElementById("mostrarLoginBtn");
    var mostrarRegistroBtn = document.getElementById("mostrarRegistroBtn");

    if (mostrarLoginBtn) {
        mostrarLoginBtn.onclick = function() {
            vistaRegistro.style.display = "none"; 
            vistaLogin.style.display = "block";
        }
    }

    if (mostrarRegistroBtn) {
        mostrarRegistroBtn.onclick = function() {
            vistaLogin.style.display = "none"; 
            vistaRegistro.style.display = "block";
        }
    }

    const toggleIcons = document.querySelectorAll(".pass_icon");

    toggleIcons.forEach(function(icon) {
        icon.onclick = function() {
            
            const input = icon.previousElementSibling;

            const openEyeSrc = "Assets/img/pass2.png"; 
            const closedEyeSrc = "Assets/img/pass.png";

            if (input.type === "password") {
                input.type = "text";
                icon.src = openEyeSrc;
            } else {
                input.type = "password";
                icon.src = closedEyeSrc;
            }
        }
    });

    const toggleIcons_2 = document.querySelectorAll(".pass_icon_2");

    toggleIcons_2.forEach(function(icon) {
        icon.onclick = function() {
            
            const input = icon.previousElementSibling;

            const openEyeSrc = "Assets/img/pass2.png"; 
            const closedEyeSrc = "Assets/img/pass.png";

            if (input.type === "password") {
                input.type = "text";
                icon.src = openEyeSrc;
            } else {
                input.type = "password";
                icon.src = closedEyeSrc;
            }
        }
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }

        if (!event.target.matches('.menu-btn')) {
            document.querySelectorAll(".menu-content.show").forEach(function(content) {
                content.classList.remove('show');
                const correspondingBtn = content.previousElementSibling;
                if (correspondingBtn) { 
                    correspondingBtn.setAttribute("aria-expanded", false);
                }
            });
        }
    }

    const setError = (input, message) => {

        input.classList.add('error');
        input.classList.remove('success');

        const wrapper = input.closest('.password-wrapper') || input;
        const errorDiv = wrapper.nextElementSibling;
        errorDiv.innerText = message;
    }

    const setSuccess = (input) => {
        input.classList.add('success');
        input.classList.remove('error');

       
        const wrapper = input.closest('.password-wrapper') || input;
        const errorDiv = wrapper.nextElementSibling;
        errorDiv.innerText = '';
    }

    const isValidEmail = (email) => {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    const formRegistro = document.getElementById('formRegistro');
    
    const nombre = document.getElementById('nombre');
    const emailReg = document.getElementById('email-reg');
    const passReg = document.getElementById('pass-reg');

    formRegistro.addEventListener('submit', function(e) {
        e.preventDefault(); 

        let esValido = validarCamposRegistro();

        if (esValido) {
            console.log("¡REGISTRO VÁLIDO!");
        } else {
            console.log("El formulario de registro tiene errores.");
        }
    });

    function validarCamposRegistro() {
        const nombreValue = nombre.value.trim();
        const emailRegValue = emailReg.value.trim();
        const passRegValue = passReg.value.trim();
        
        let todoCorrecto = true; 

        if (nombreValue === '') {
            setError(nombre, 'El nombre no puede estar vacío');
            todoCorrecto = false;
        } else {
            setSuccess(nombre);
        }


        if (emailRegValue === '') {
            setError(emailReg, 'El email no puede estar vacío');
            todoCorrecto = false;
        } else if (!isValidEmail(emailRegValue)) {
            setError(emailReg, 'El formato del email es incorrecto');
            todoCorrecto = false;
        } else {
            setSuccess(emailReg);
        }

        if (passRegValue === '') {
            setError(passReg, 'La contraseña no puede estar vacía');
            todoCorrecto = false;
        } else if (passRegValue.length < 6) {
            setError(passReg, 'Debe tener al menos 6 caracteres');
            todoCorrecto = false;
        } else {
            setSuccess(passReg);
        }

        return todoCorrecto;
    }

    const formLogin = document.getElementById('formLogin');
    const emailLogin = document.getElementById('email-login');
    const passLogin = document.getElementById('pass-login');
    
    formLogin.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let esValido = validarCamposLogin();

        if (esValido) {
            console.log("¡LOGIN VÁLIDO! Enviando al servidor...");

        } else {
            console.log("El formulario de login tiene errores.");
        }
    });

    function validarCamposLogin() {
        const emailLoginValue = emailLogin.value.trim();
        const passLoginValue = passLogin.value.trim();
        let todoCorrecto = true;

        if (emailLoginValue === '') {
            setError(emailLogin, 'El email no puede estar vacío');
            todoCorrecto = false;
        } else {
            setSuccess(emailLogin);
        }

        if (passLoginValue === '') {
            setError(passLogin, 'La contraseña no puede estar vacía');
            todoCorrecto = false;
        } else {
            setSuccess(passLogin);
        }

        return todoCorrecto;
    }
});