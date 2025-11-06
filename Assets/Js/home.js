document.addEventListener("DOMContentLoaded", function() {

  const allmenuBtns = document.querySelectorAll(".menu-btn");

  allmenuBtns.forEach(function(btn) {
    
    btn.onclick = function(e) {
      e.stopPropagation(); 
      
      const content = this.nextElementSibling;
      const isExpanded = content.classList.toggle("show");
      this.setAttribute("aria-expanded", isExpanded);
    };
  });

  window.onclick = function(event) {
    document.querySelectorAll(".menu-content").forEach(function(content) {
      if (content.classList.contains('show')) {
        content.classList.remove('show');
        
        const correspondingBtn = content.previousElementSibling;
        correspondingBtn.setAttribute("aria-expanded", false);
      }
    });
  }
});