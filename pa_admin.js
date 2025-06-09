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
            }).catch(() => materiaSelect.innerHTML = '<option value="">Error de conexión</option>');
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
            }).catch(() => temaSelect.innerHTML = '<option value="">Error de conexión</option>');
    }

    if(materiaSelect && temaSelect){
        materiaSelect.addEventListener('change', () => cargarTemas(materiaSelect.value));
        cargarMaterias();
    }
    
    const questionForm = document.getElementById('pa-question-form');
    if(questionForm) {
        const dificultadInput = document.getElementById('dificultad_valor');
        let selectedLevel = dificultadInput ? parseInt(dificultadInput.value) : 3;

        function highlightFueguitos(level) {
            document.querySelectorAll('.fueguito').forEach((f, index) => {
                f.classList.toggle('selected', index < level);
            });
        }

        document.querySelectorAll('.fueguito').forEach(fueguito => {
            const level = parseInt(fueguito.dataset.level);
            fueguito.addEventListener('mouseover', () => highlightFueguitos(level));
            fueguito.addEventListener('mouseout', () => highlightFueguitos(selectedLevel));
            fueguito.addEventListener('click', () => {
                selectedLevel = level;
                dificultadInput.value = selectedLevel;
                highlightFueguitos(selectedLevel);
            });
        });

        highlightFueguitos(selectedLevel);

        const deleteButton = questionForm.querySelector('.delete-button');
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                if (confirm('¿Estás seguro que deseas limpiar todos los campos del formulario?')) {
                    questionForm.reset();
                    document.getElementById('pregunta-preview').innerHTML = '';
                    document.getElementById('respuesta-preview').innerHTML = '';
                    dificultadInput.value = 3;
                    selectedLevel = 3;
                    highlightFueguitos(selectedLevel);
                    temaSelect.innerHTML = '<option value="">Selecciona una materia primero</option>';
                }
            });
        }
        
        function setupImagePreview(inputId, previewId) {
            const input = questionForm.querySelector(`input[name="${inputId}"]`);
            const preview = document.getElementById(previewId);
            if(input && preview) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            preview.innerHTML = `<img src="${event.target.result}" alt="Vista previa de imagen">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = '';
                    }
                });
            }
        }

        setupImagePreview('pregunta_imagen', 'pregunta-preview');
        setupImagePreview('respuesta_imagen', 'respuesta-preview');
    }
});