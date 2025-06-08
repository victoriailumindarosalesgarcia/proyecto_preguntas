

 document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;


   // --- Verificación de Sesión ---
   function verificarSesion() {
       fetch('pensamientocomputacionalparalaingenieria.php?action=check_session')
           .then(response => {
               if (!response.ok) {
                   window.location.href = 'login.html'; 
                   throw new Error('Error en la respuesta del servidor al verificar sesión.');
               }
               return response.json();
           })
           .then(data => {
               if (data.error && data.redirect) {
                   window.location.href = data.redirect;
               } else if (data.success) {
                   console.log('Sesión válida para ver contenido de la materia.');
               } else {
        
                   window.location.href = 'login.html';
               }
           })
           .catch(error => {
               console.error('Error al verificar la sesión:', error);
             
           });
   }

   verificarSesion();


   // --- Funcionalidad del Menú Desplegable ---
   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }


   const enlaces = document.querySelectorAll('a');
   const botones = document.querySelectorAll('button');


   enlaces.forEach(enlace => {
       enlace.addEventListener('click', e => {
           e.preventDefault();
           window.location.href = 'racha_profe.html';
       });
   });


   botones.forEach(boton => {
       boton.addEventListener('click', () => {
           window.location.href = 'racha_profe.html';
       });
   });
});


  
