document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;
   const backButton = document.getElementById("dynamicBackButton");


   const materiaNameTitleElement = document.getElementById("materiaNameTitle");
   const topicsListContainer = document.getElementById("topicsListContainer");
   const loadingTopicsMessage = document.getElementById("loadingTopicsMessage");
   const noTopicsMessage = document.getElementById("noTopicsMessage");
   const addTopicBtn = document.getElementById("addTopicBtn");


   const urlParams = new URLSearchParams(window.location.search);
   const materiaId = urlParams.get('materia_id');


   if (!materiaId) {
       if (materiaNameTitleElement) materiaNameTitleElement.textContent = "Materia no especificada";
       if (loadingTopicsMessage) loadingTopicsMessage.style.display = 'none';
       if (noTopicsMessage) {
           noTopicsMessage.textContent = "Por favor, selecciona una materia para ver sus temas.";
           noTopicsMessage.style.display = 'block';
       }
   }


   function verificarSesionYCargarTemas() {
       if (!materiaId) return; 


       fetch(`temas_admin.php?action=get_topics&materia_id=${materiaId}`)
           .then(response => {
               if (!response.ok) {
                   window.location.href = 'login.html';
                   throw new Error('Error en la respuesta del servidor.');
               }
               return response.json();
           })
           .then(data => {
               if (loadingTopicsMessage) loadingTopicsMessage.style.display = 'none';
               if (data.error && data.redirect) {
                   window.location.href = data.redirect;
               } else if (data.success) {
                   if (materiaNameTitleElement && data.materia_nombre) {
                       materiaNameTitleElement.textContent = data.materia_nombre;
                   }
                   displayTopics(data.topics);
               } else if (data.error) {
                   if (noTopicsMessage) {
                       noTopicsMessage.textContent = data.error;
                       noTopicsMessage.style.display = 'block';
                   }
                   console.error("Error del servidor:", data.error);
               } else {
                    if (noTopicsMessage) {
                       noTopicsMessage.textContent = 'No se pudieron cargar los temas para esta materia.';
                       noTopicsMessage.style.display = 'block';
                   }
               }
           })
           .catch(error => {
               console.error('Error al verificar sesión o cargar temas:', error);
               if (loadingTopicsMessage) loadingTopicsMessage.style.display = 'none';
               if (noTopicsMessage) {
                   noTopicsMessage.textContent = 'Error al cargar los temas. Intenta de nuevo más tarde.';
                   noTopicsMessage.style.display = 'block';
               }
           });
   }


   function displayTopics(topics) {
       if (!topicsListContainer) return;
       topicsListContainer.innerHTML = '';


       if (!topics || topics.length === 0) {
           if (noTopicsMessage) {
               noTopicsMessage.textContent = 'No hay temas registrados para esta materia.';
               noTopicsMessage.style.display = 'block';
           }
           return;
       }
       if (noTopicsMessage) noTopicsMessage.style.display = 'none';


       topics.forEach(topic => {
           const listItem = document.createElement('li');
           listItem.className = 'list-item';


           const nameSpan = document.createElement('span');
           nameSpan.className = 'item-name';
           nameSpan.textContent = topic.nombre_tema;
           listItem.appendChild(nameSpan);

           const actionsDiv = document.createElement('div');
           actionsDiv.className = 'item-actions';
           listItem.appendChild(actionsDiv);
          
           topicsListContainer.appendChild(listItem);
       });
   }


   if (materiaId) { 
       verificarSesionYCargarTemas();
   }




   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }


       if (backButton) {
        const urlParams = new URLSearchParams(window.location.search);
        const fromParam = urlParams.get('from');
        

        let backUrl;
        
        if (fromParam) {

            backUrl = decodeURIComponent(fromParam);
        } else if (document.referrer && 
                 document.referrer.includes(window.location.hostname) &&
                 !document.referrer.includes('temas_admin.html')) {
          
            backUrl = document.referrer;
        } else {
    
            backUrl = 'dashboard_admin.html';
        }

        backButton.href = backUrl;
    }
});
