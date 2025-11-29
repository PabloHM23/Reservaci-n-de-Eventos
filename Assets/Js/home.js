document.addEventListener("DOMContentLoaded", function() {

    const allmenuBtns = document.querySelectorAll(".menu-btn");
    allmenuBtns.forEach(function(btn) {
        btn.onclick = function(e) {
            
            if (this.classList.contains('user-welcome')) {
                return;
            }

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
    
    var vistaRegistro = document.getElementById("vistaRegistro");
    var vistaLogin = document.getElementById("vistaLogin");
    var mostrarLoginBtn = document.getElementById("mostrarLoginBtn");
    var mostrarRegistroBtn = document.getElementById("mostrarRegistroBtn");


    if (btnAbrirModal) {
        btnAbrirModal.onclick = function() {
            if (modal) modal.style.display = "flex";
            if (vistaRegistro) vistaRegistro.style.display = "none"; 
            if (vistaLogin) vistaLogin.style.display = "block";
        }
    }

    if (btnAbrirRegistro) {
    btnAbrirRegistro.onclick = function() {
        if (modal) modal.style.display = "flex";
        if (vistaLogin) vistaLogin.style.display = "none"; 
        if (vistaRegistro) vistaRegistro.style.display = "block";
    }
}

    for (let i = 0; i < spanCerrar.length; i++) {
        spanCerrar[i].onclick = function() {
            if (modal) modal.style.display = "none";
        }
    }

    if (mostrarLoginBtn) {
        mostrarLoginBtn.onclick = function() {
            if (vistaRegistro) vistaRegistro.style.display = "none"; 
            if (vistaLogin) vistaLogin.style.display = "block";
        }
    }

    if (mostrarRegistroBtn) {
        mostrarRegistroBtn.onclick = function() {
            if (vistaLogin) vistaLogin.style.display = "none"; 
            if (vistaRegistro) vistaRegistro.style.display = "block";
        }
    }

    const toggleIcons = document.querySelectorAll(".pass_icon");
    toggleIcons.forEach(function(icon) {
        icon.onclick = function() {
            
            const input = icon.previousElementSibling;

            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    });

    const toggleIcons_2 = document.querySelectorAll(".pass_icon_2");
    toggleIcons_2.forEach(function(icon) {
        icon.onclick = function() {
            
            const input = icon.previousElementSibling;

            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            if (modal) modal.style.display = "none";
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

    window.setError = (input, message) => {
        if (!input) return;

        input.classList.add('error');
        input.classList.remove('success');

        const wrapper = input.closest('.password-wrapper') || input;
        const errorDiv = wrapper.nextElementSibling;
        if (errorDiv) errorDiv.innerText = message;
    }

    window.setSuccess = (input) => {
        if (!input) return;
        
        input.classList.add('success');
        input.classList.remove('error');

        const wrapper = input.closest('.password-wrapper') || input;
        const errorDiv = wrapper.nextElementSibling;
        if (errorDiv) errorDiv.innerText = '';
    }

    window.isValidEmail = (email) => {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

});