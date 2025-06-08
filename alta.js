document.addEventListener("DOMContentLoaded", () => {
   const form = document.querySelector("form");

    form.addEventListener("submit", (e) => {
     const nombre = document.getElementById("nombre").value.trim();
     const correo = document.getElementById("correo").value.trim();
     const password = document.getElementById("password").value.trim();
      if (!nombre || !correo || !password) {
       alert("Por favor, completa todos los campos.");
       e.preventDefault();
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

