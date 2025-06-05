document.addEventListener("DOMContentLoaded", () => {
   const menuIcon = document.getElementById("menuIcon");
   const sidebar = document.getElementById("sidebar");
   const body = document.body;


   const rachaTitleElement = document.getElementById("rachaTitle");
   const questionsListContainer = document.getElementById("questionsListContainer");
   const loadingMessage = document.getElementById("loadingMessage");
   const noQuestionsMessage = document.getElementById("noQuestionsMessage");
   const addNewQuestionBtn = document.getElementById("addNewQuestionBtn");


   const urlParams = new URLSearchParams(window.location.search);
   const topic = urlParams.get('topic') || 'General'; 


   if (rachaTitleElement) {
       rachaTitleElement.innerHTML = `Preguntas sobre: ${decodeURIComponent(topic)} <span class="topic-icon">📄</span>`;
   }
   if (addNewQuestionBtn) {
       addNewQuestionBtn.href = `opcion_admin.html?topic=${encodeURIComponent(topic)}`;
   }



   if (menuIcon && sidebar) {
       menuIcon.addEventListener("click", () => {
           sidebar.classList.toggle("open");
           body.classList.toggle("sidebar-open");
       });
   }

   if (addNewQuestionBtn && topic) {
    addNewQuestionBtn.href = `question_type_selection.html?topic=${encodeURIComponent(topic)}`;
}
});
