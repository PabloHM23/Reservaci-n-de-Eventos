// Espera a que todo el contenido del DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {

  // 1. Configuración del selector de FECHA
  flatpickr("#fecha-evento", {
    dateFormat: "d/m/Y", // Formato día/mes/año
    placeholder: "dd/mm/aaaa", // El placeholder que se ve
  });

  // 2. Configuración del selector de HORA
  flatpickr("#hora-inicio", {
    enableTime: true,   // Activa la selección de hora
    noCalendar: true,   // Desactiva el calendario, solo muestra la hora
    dateFormat: "h:i K", // Formato 12 horas (h) con minutos (i) y AM/PM (K)
    defaultHour: 18,      // Hora por defecto (6 PM)
    defaultMinute: 30,
    placeholder: "06:30 p.m."
  });
  
  // 3. (Opcional) Leer los datos al publicar
  const publicarBtn = document.querySelector('.btn-publicar');
  publicarBtn.addEventListener('click', () => {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha-evento').value;
    const hora = document.getElementById('hora-inicio').value;
    const capacidad = document.getElementById('capacidad').value;
    const esVisible = document.getElementById('visibilidad').checked;
    
    console.log('--- Evento a Publicar ---');
    console.log('Nombre:', nombre);
    console.log('Fecha:', fecha);
    console.log('Hora:', hora);
    console.log('Capacidad:', capacidad);
    console.log('Visible:', esVisible);
  });

});