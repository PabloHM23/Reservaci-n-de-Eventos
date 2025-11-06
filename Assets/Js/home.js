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

        document.querySelectorAll(".menu-content.show").forEach(function(content) {
            content.classList.remove('show');
            const correspondingBtn = content.previousElementSibling;
            if (correspondingBtn) { 
                correspondingBtn.setAttribute("aria-expanded", false);
            }
        });
    }
});