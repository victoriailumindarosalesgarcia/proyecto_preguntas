document.addEventListener("DOMContentLoaded", () => {
   const form = document.querySelector("form");
    const menuIcon = document.getElementById("menuIcon");
    const sidebar = document.getElementById("sidebar");
    const body = document.body;
    const backButton = document.getElementById("dynamicBackButton");

    form.addEventListener("submit", (e) => {
     const nombre = document.getElementById("nombre").value.trim();
     const correo = document.getElementById("correo").value.trim();
     const password = document.getElementById("password").value.trim();
      if (!nombre || !correo || !password) {
       alert("Por favor, completa todos los campos.");
       e.preventDefault();
     }
   });

   // --- Funcionalidad del Menú Desplegable ---
    if (menuIcon && sidebar) {
        menuIcon.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            body.classList.toggle("sidebar-open");
        });
    }

    // --- Lógica para el botón de volver atrás ---
    /*if (backButton) {
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        
    
        let backUrl;
        
        if (fromParam) {

            backUrl = decodeURIComponent(fromParam);
        } else if (document.referrer && 
                 document.referrer.includes(window.location.hostname) &&
                 !document.referrer.includes('alta.html')) {
        
            backUrl = document.referrer;
        } else {
            backUrl = 'dashboard_admin.html';
        }

        backButton.href = backUrl;
    }*/
});


