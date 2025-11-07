document.addEventListener('DOMContentLoaded', function() {

  flatpickr("#fecha-evento", {
    dateFormat: "d/m/Y",
    placeholder: "dd/mm/aaaa",
    allowInput: false
  });

  flatpickr("#hora-inicio", {
    enableTime: true, 
    noCalendar: true,   
    dateFormat: "h:i K", 
    defaultHour: 18,      
    defaultMinute: 30,
    placeholder: "06:30 p.m.",
    allowInput: false
  });

  const publicarBtn = document.querySelector('.btn-publicar');
  publicarBtn.addEventListener('click', () => {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha-evento').value;
    const hora = document.getElementById('hora-inicio').value;
    const capacidad = document.getElementById('capacidad').value;
    const esVisible = document.getElementById('visibilidad').checked;

    if (!nombre) {
      alert('Por favor, escribe un nombre para el evento.');
      return; 
    }
    if (!fecha) {
      alert('Por favor, selecciona una fecha.');
      return; 
    }
    if (!hora) {
      alert('Por favor, selecciona una hora de inicio.');
      return; 
    }
    if (!capacidad || parseInt(capacidad) <= 0) {
      alert('Por favor, define una capacidad válida.');
      return;
    }
    
    console.log('Evento a Publicar Validado');
    console.log('Nombre:', nombre);
    console.log('Fecha:', fecha);
    console.log('Hora:', hora);
    console.log('Capacidad:', capacidad);
    console.log('Visible:', esVisible);
  });

});