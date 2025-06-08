document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgot-password-form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
 
        fetch('forgot_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Se ha enviado un enlace para restablecer tu contraseña a tu correo electrónico.');
            } else {
                alert(data.message || 'Error al enviar el enlace. Por favor intenta nuevamente.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error. Por favor intenta nuevamente.');
        });
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