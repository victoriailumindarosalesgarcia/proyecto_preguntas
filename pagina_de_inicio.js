document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;


   function checkIfLoggedIn() {
       fetch('pagina_inicio.php?action=check_if_logged_in')
           .then(response => {
               if (!response.ok) {
        
                   console.warn('Advertencia: No se pudo verificar el estado de la sesión.');
                   return null;
               }
               return response.json();
           })
           .then(data => {
               if (data && data.loggedIn && data.redirect_url) {
     
                   window.location.href = data.redirect_url;
               } else if (data && data.loggedIn) {
             
                   console.log('Usuario logueado, redirigiendo a dashboard por defecto...');
                   window.location.href = 'dashboard.php'; 
               }
             
           })
           .catch(error => {
               console.error('Error al verificar si el usuario está logueado:', error);
   
           });
   }


   checkIfLoggedIn();


   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }
});
