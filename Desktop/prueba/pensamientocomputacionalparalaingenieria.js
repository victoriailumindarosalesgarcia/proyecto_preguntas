document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;
   const backButton = document.getElementById("dynamicBackButton");

   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }

   if (backButton) {
        
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        

        let backUrl;
        
        if (fromParam) {
 
            backUrl = decodeURIComponent(fromParam);
        } else if (document.referrer && 
                 document.referrer.includes(window.location.hostname) &&
                 !document.referrer.includes('pensamientocomputancionalparalaingenieria.html')) {
        
            backUrl = document.referrer;
        } else {
    
            backUrl = 'materias_admin.html';
        }

     
        backButton.href = backUrl;
    }
});
