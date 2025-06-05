document.addEventListener("DOMContentLoaded", () => {
   const form = document.getElementById("loginForm");
    form.addEventListener("submit", function (e) {
     const email = document.getElementById("email").value.trim();
     const password = document.getElementById("password").value.trim();
      if (!email || !password) {
       alert("Por favor, completa todos los campos.");
       e.preventDefault(); // Evita que el formulario se envíe
     }
   });
 });

 document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;

   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }
});