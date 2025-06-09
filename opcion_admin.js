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

    const materiaSelect = document.getElementById('materiaSelect');
    const temaSelect = document.getElementById('temaSelect');

    function cargarMaterias() {
        fetch('get_data.php?action=get_materias')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    materiaSelect.innerHTML = '<option value="">-- Elige una materia --</option>';
                    data.materias.forEach(materia => {
                        const option = new Option(materia.nombre, materia.id_materia);
                        materiaSelect.add(option);
                    });
                } else {
                    materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
                }
            }).catch(() => {
                materiaSelect.innerHTML = '<option value="">Error de conexión</option>';
            });
    }

    function cargarTemas(idMateria) {
        temaSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!idMateria) {
            temaSelect.innerHTML = '<option value="">Selecciona una materia primero</option>';
            return;
        }

        fetch(`get_data.php?action=get_temas&id_materia=${idMateria}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.temas.length > 0) {
                    temaSelect.innerHTML = '<option value="">-- Elige un tema --</option>';
                    data.temas.forEach(tema => {
                        const option = new Option(tema.nombre, tema.id_tema);
                        temaSelect.add(option);
                    });
                } else {
                    temaSelect.innerHTML = '<option value="">No hay temas para esta materia</option>';
                }
            }).catch(() => {
                temaSelect.innerHTML = '<option value="">Error de conexión</option>';
            });
    }

    if(materiaSelect && temaSelect){
        materiaSelect.addEventListener('change', () => cargarTemas(materiaSelect.value));
        cargarMaterias();
    }
   
    const questionForm = document.getElementById('question-form');
    if (questionForm) {
        // ... (Aquí va todo tu código JS anterior para este formulario: fueguitos, previews de imágenes, etc.)
    }
});