<?php
session_start();
header('Content-Type: application/json');

function getDbConnection() {
   $servidor = "localhost";
   $usuario_db = "root";
   $password_db = "";
   $nombre_db = "pag";
   $conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
   if ($conexion->connect_error) {
       return null;
   }
   $conexion->set_charset("utf8");
   return $conexion;
}


$action = $_GET['action'] ?? '';


if ($action === 'check_session') { 
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado.', 'redirect' => 'login.html']);
   } else {
       echo json_encode(['success' => true, 'message' => 'Sesión válida.']);
   }
   exit;
}


if ($action === 'get_racha_questions') {
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado.', 'redirect' => 'login.html']);
       exit;
   }


   $topic = $_GET['topic'] ?? 'General'; 
   $conexion = getDbConnection();


   if (!$conexion) {
       echo json_encode(['error' => 'Error de conexión a la base de datos.']);
       exit;
   }


   $questions = [];
  
   $sql_opcion = "SELECT id AS id_pregunta, texto, dificultad, 'opcion_multiple' AS tipo_pregunta
                  FROM preguntas
                  WHERE texto LIKE ? OR tema = ?"; 
   $stmt_opcion = $conexion->prepare($sql_opcion);
   $searchTerm = "%" . $topic . "%";
   $stmt_opcion->bind_param("ss", $searchTerm, $topic); 
   $stmt_opcion->execute();
   $resultado_opcion = $stmt_opcion->get_result();
   while ($fila = $resultado_opcion->fetch_assoc()) {
       $fila['texto_pregunta'] = htmlspecialchars($fila['texto']);
       $questions[] = $fila;
   }
   $stmt_opcion->close();


   $sql_abierta = "SELECT id AS id_pregunta, texto_pregunta, dificultad, 'abierta' AS tipo_pregunta
                   FROM preguntas_abiertas
                   WHERE texto_pregunta LIKE ? OR tema = ?"; 
   $stmt_abierta = $conexion->prepare($sql_abierta);
   $stmt_abierta->bind_param("ss", $searchTerm, $topic); 
   $stmt_abierta->execute();
   $resultado_abierta = $stmt_abierta->get_result();
   while ($fila = $resultado_abierta->fetch_assoc()) {
       $fila['texto_pregunta'] = htmlspecialchars($fila['texto_pregunta']);
       $questions[] = $fila;
   }
   $stmt_abierta->close();
  
   $conexion->close();



   if (empty($questions) && $topic !== 'General') { // Solo si se buscó un tema específico y no hay nada
        echo json_encode(['success' => true, 'questions' => [], 'message' => 'No se encontraron preguntas para el tema: ' . htmlspecialchars($topic)]);
   } else {
       echo json_encode(['success' => true, 'questions' => $questions]);
   }
   exit;


} else {
   echo json_encode(['error' => 'Acción no válida o no especificada.']);
   exit;
}
?>
