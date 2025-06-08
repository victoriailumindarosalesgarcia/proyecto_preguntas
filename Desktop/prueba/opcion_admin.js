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


   
   const questionForm = document.getElementById('question-form');
   if (questionForm) {
    
       document.querySelectorAll('.correct-radio').forEach(radio => {
           radio.addEventListener('change', function() {
               document.querySelectorAll('.option').forEach(opt => {
                   opt.classList.remove('correct');
               });
               if (this.checked) {
                   this.closest('.option').classList.add('correct');
               }
           });
       });



       const checkedRadio = document.querySelector('.correct-radio:checked');
       if (checkedRadio) {
           checkedRadio.closest('.option').classList.add('correct');
       } else {
    
           const firstRadio = document.querySelector('.correct-radio');
           if (firstRadio) {
               firstRadio.checked = true;
               firstRadio.closest('.option').classList.add('correct');
           }
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

    // Eventos
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


       const deleteButton = document.querySelector('.delete-button');
       if (deleteButton) {
           deleteButton.addEventListener('click', function() {
               if (confirm('¿Estás seguro que deseas eliminar esta pregunta y sus datos? Esta acción limpiará el formulario.')) {
                   questionForm.reset();


                   document.querySelectorAll('.image-preview').forEach(preview => {
                       preview.innerHTML = '';
                   });


                   document.querySelectorAll('.option').forEach(opt => {
                       opt.classList.remove('correct');
                   });
                   const firstRadio = document.querySelector('.correct-radio');
                   if (firstRadio) {
                       firstRadio.checked = true;
                       firstRadio.closest('.option').classList.add('correct');
                   }
                  
                   if(dificultadInput) dificultadInput.value = 3;
                   fueguitos.forEach((f, index) => {
                       if (index < 3) f.classList.add('selected');
                       else f.classList.remove('selected');
                   });
               }
           });
       }



       document.querySelectorAll('.image-upload-input').forEach(input => {
           input.addEventListener('change', function(e) {
               const file = e.target.files[0];
               let previewContainer;
               if (this.name === 'pregunta_imagen') {
                   previewContainer = document.getElementById('pregunta-preview');
               } else {
                   const parentOption = this.closest('.option');
                   if (parentOption) {
                       previewContainer = parentOption.querySelector('.option-image-preview');
                   }
               }


               if (!file || !previewContainer) {
                   if (file && !previewContainer) console.warn('No se encontró contenedor de preview para', this.name);
                   if(!file && previewContainer) previewContainer.innerHTML = '';
                   return;
               }
              
               if (!file.type.match('image.*')) {
                   alert('Por favor, selecciona un archivo de imagen válido (JPEG, PNG, GIF).');
                   this.value = '';
                   previewContainer.innerHTML = ''; 
                   return;
               }
              
               const reader = new FileReader();
               reader.onload = function(event) {
                   previewContainer.innerHTML = ''; 
                   const img = document.createElement('img');
                   img.src = event.target.result;
                   previewContainer.appendChild(img);
               };
               reader.readAsDataURL(file);
           });
       });
   }
});
