document.addEventListener("DOMContentLoaded", () => {
    // Cargar datos de ejemplo (en producción sería una petición AJAX)
    const questions = [
        { id: 1, text: "¿Cuál es la capital de Francia?", type: "opcion", difficulty: 3, status: "aprobada" },
        { id: 2, text: "Explica la teoría de la relatividad", type: "abierta", difficulty: 5, status: "pendiente" }
    ];

    function renderQuestions(filteredQuestions) {
        const tbody = document.querySelector("#questionsTable tbody");
        tbody.innerHTML = "";

        filteredQuestions.forEach(q => {
            const row = document.createElement("tr");
            
            row.innerHTML = `
                <td>${q.id}</td>
                <td>${q.text}</td>
                <td>${getTypeName(q.type)}</td>
                <td>${renderDifficulty(q.difficulty)}</td>
                <td><span class="status-badge ${q.status}">${getStatusName(q.status)}</span></td>
                <td class="action-buttons">
                    <button class="edit-btn" data-id="${q.id}">Editar</button>
                    <button class="delete-btn" data-id="${q.id}">Eliminar</button>
                </td>
            `;
            
            tbody.appendChild(row);
        });

        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const id = e.target.getAttribute("data-id");
                editQuestion(id);
            });
        });

        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const id = e.target.getAttribute("data-id");
                deleteQuestion(id);
            });
        });
    }

    function getTypeName(type) {
        const types = {
            "opcion": "Opción Múltiple",
            "abierta": "Abierta",
            "vf": "V/F"
        };
        return types[type] || type;
    }

    function getStatusName(status) {
        const statuses = {
            "aprobada": "Aprobada",
            "pendiente": "Pendiente",
            "rechazada": "Rechazada"
        };
        return statuses[status] || status;
    }

    function renderDifficulty(level) {
        let html = "";
        for (let i = 1; i <= 5; i++) {
            html += `<span class="fueguito ${i <= level ? 'selected' : ''}">🔥</span>`;
        }
        return html;
    }

    function editQuestion(id) {
        const question = questions.find(q => q.id == id);
        if (question.type === "opcion") {
            window.location.href = `opcion_admin.html?edit=${id}`;
        } else if (question.type === "abierta") {
            window.location.href = `pa_admin.html?edit=${id}`;
        }
    }

    function deleteQuestion(id) {
        if (confirm("¿Estás seguro de eliminar esta pregunta?")) {
            // Aquí iría la petición AJAX para eliminar
            alert(`Pregunta ${id} eliminada (simulado)`);
            renderQuestions(questions.filter(q => q.id != id));
        }
    }

    document.getElementById("applyFilters").addEventListener("click", () => {
        const typeFilter = document.getElementById("filterType").value;
        const statusFilter = document.getElementById("filterStatus").value;
        
        let filtered = questions;
        
        if (typeFilter !== "all") {
            filtered = filtered.filter(q => q.type === typeFilter);
        }
        
        if (statusFilter !== "all") {
            filtered = filtered.filter(q => q.status === statusFilter);
        }
        
        renderQuestions(filtered);
    });

    renderQuestions(questions);
});

document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;
   const backButton = document.getElementById("dynamicBackButton");


   const adminGreetingElement = document.getElementById("adminGreeting");
   const adminEmailElement = document.getElementById("adminEmail");


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
                 !document.referrer.includes('preguntas_admin.html')) {
            backUrl = document.referrer;
        } else {
            backUrl = 'dashboard_admin.html';
        }
        backButton.href = backUrl;
    }

});