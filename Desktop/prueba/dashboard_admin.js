document.addEventListener("DOMContentLoaded", () => {
    const menuIcon = document.getElementById("menuIcon");
    const sidebar = document.getElementById("sidebar");
    const body = document.body;

    if (menuIcon && sidebar) {
        menuIcon.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            body.classList.toggle("sidebar-open");
        });
    }

    fetch('dashboard_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalMaterias').textContent = data.totalMaterias || 0;
                document.getElementById('totalPreguntas').textContent = data.totalPreguntas || 0;
                document.getElementById('totalTemas').textContent = data.totalTemas || 0;
            }
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });
});