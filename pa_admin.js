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

        const dificultadInput = document.getElementById('dificultad_valor');
        const fueguitos = document.querySelectorAll('.fueguito');


        const fueguitosContainer = document.querySelector('.fueguitos');
        let selectedLevel = 3; 

document.querySelectorAll('.fueguito').forEach(fueguito => {
 
    const level = parseInt(fueguito.dataset.level);
    if (level <= selectedLevel) {
        fueguito.classList.add('selected');
    }

 
    fueguito.addEventListener('mouseover', function() {
        highlightFueguitos(level);
    });

    fueguito.addEventListener('mouseout', function() {
        highlightFueguitos(selectedLevel);
    });

    fueguito.addEventListener('click', function() {
        selectedLevel = level;
        document.getElementById('dificultad').value = selectedLevel;
        highlightFueguitos(selectedLevel);
    });
});

function highlightFueguitos(level) {
    document.querySelectorAll('.fueguito').forEach((f, index) => {
        if (index < level) {
            f.classList.add('selected');
        } else {
            f.classList.remove('selected');
        }
    });
}



       const initialDifficulty = dificultadInput ? parseInt(dificultadInput.value) : 3;
       fueguitos.forEach((f, index) => {
           if (index < initialDifficulty) f.classList.add('selected');
       });

  
       const deleteButton = questionForm.querySelector('.delete-button');
       if (deleteButton) {
           deleteButton.addEventListener('click', function() {
               if (confirm('¿Estás seguro que deseas limpiar todos los campos del formulario?')) {
                   questionForm.reset();


                  
                   const preguntaPreview = document.getElementById('pregunta-preview');
                   if (preguntaPreview) preguntaPreview.innerHTML = '';
                  
                  
                   if(dificultadInput) dificultadInput.value = 3;
                   fueguitos.forEach((f, index) => {
                       if (index < 3) f.classList.add('selected');
                       else f.classList.remove('selected');
                   });
               }
           });
       }


       const preguntaImagenInput = questionForm.querySelector('input[name="pregunta_imagen"]');
       const preguntaPreview = document.getElementById('pregunta-preview');


       if (preguntaImagenInput && preguntaPreview) {
           preguntaImagenInput.addEventListener('change', function(e) {
               const file = e.target.files[0];
              
               if (!file) {
                   preguntaPreview.innerHTML = ''; 
                   return;
               }
              
               if (!file.type.match('image.*')) {
                   alert('Por favor, selecciona un archivo de imagen válido (JPEG, PNG, GIF).');
                   this.value = '';
                   preguntaPreview.innerHTML = '';
                   return;
               }
              
               const reader = new FileReader();
               reader.onload = function(event) {
                   preguntaPreview.innerHTML = '';
                   const img = document.createElement('img');
                   img.src = event.target.result;
                   preguntaPreview.appendChild(img);
               };
               reader.readAsDataURL(file);
           });
       }
    
        const respuestaImagenInput = questionForm.querySelector('input[name="respuesta_imagen"]');
        const respuestaPreview = document.getElementById('respuesta-preview');

if (respuestaImagenInput && respuestaPreview) {
    respuestaImagenInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
            respuestaPreview.innerHTML = '';
            return;
        }
        
        if (!file.type.match('image.*')) {
            alert('Por favor, selecciona un archivo de imagen válido (JPEG, PNG, GIF).');
            this.value = '';
            respuestaPreview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            respuestaPreview.innerHTML = '';
            const img = document.createElement('img');
            img.src = event.target.result;
            respuestaPreview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

});;
