document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  form.addEventListener("submit", function (e) {
    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value.trim();
    if (!correo || !password) {
      alert("Por favor, completa todos los campos.");
      e.preventDefault(); // Detiene el envío
    }
  });

  const menuIcon = document.getElementById("menuIcon");
  const sidebar = document.getElementById("sidebar");
  const body = document.body;

  if (menuIcon && sidebar) {
    menuIcon.addEventListener("click", () => {
      sidebar.classList.toggle("open");
      body.classList.toggle("sidebar-open");
    });
  }
});