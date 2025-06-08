document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
       const backButton = document.getElementById("dynamicBackButton");
   const body = document.body;

   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }

    const urlParams = new URLSearchParams(window.location.search);
    const materiaId = urlParams.get('materia_id');

    // --- Lógica para el botón de volver atrás ---
    if (backButton) {
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        
        let backUrl;
        
        if (fromParam) {
            backUrl = decodeURIComponent(fromParam);
        } else if (document.referrer && 
                 document.referrer.includes(window.location.hostname) &&
                 !document.referrer.includes('add_tema.html')) {
            backUrl = document.referrer;
        } else {
            backUrl = 'dashboard_admin.html';
        }
        backButton.href = backUrl;
    }
});