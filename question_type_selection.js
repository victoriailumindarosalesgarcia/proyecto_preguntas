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

    // --- Lógica para el botón de volver atrás ---
    if (backButton) {
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        
    
        let backUrl;
        
        if (fromParam) {

            backUrl = decodeURIComponent(fromParam);
        } else if (document.referrer && 
                 document.referrer.includes(window.location.hostname) &&
                 !document.referrer.includes('question_type_selection.html')) {
        
            backUrl = document.referrer;
        } else {
            backUrl = 'preguntas_admin.html';
        }

        backButton.href = backUrl;
    }
});
