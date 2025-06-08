document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;


   const adminGreetingElement = document.getElementById("adminGreeting");
   const adminEmailElement = document.getElementById("adminEmail");


   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }

});

