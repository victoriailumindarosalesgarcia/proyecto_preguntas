<?php
session_start();
header('Content-Type: application/json');


function getDbConnection() {
   $servidor = "localhost"; $usuario_db = "root"; $password_db = ""; $nombre_db = "pag";
   $conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
   if ($conexion->connect_error) { return null; }
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


if ($action === 'get_topics') {
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado.', 'redirect' => 'login.html']);
       exit;
   }


   $materia_id = filter_input(INPUT_GET, 'materia_id', FILTER_VALIDATE_INT);


   if (!$materia_id) {
       echo json_encode(['error' => 'ID de materia no válido o no proporcionado.']);
       exit;
   }


   $conexion = getDbConnection();
   if (!$conexion) {
       echo json_encode(['error' => 'Error de conexión a la base de datos.']);
       exit;
   }


   $materia_nombre = "Desconocida";
   $sql_materia_nombre = "SELECT nombre FROM materias WHERE id = ? LIMIT 1"; 
   $stmt_materia = $conexion->prepare($sql_materia_nombre);
   if ($stmt_materia) {
       $stmt_materia->bind_param("i", $materia_id);
       $stmt_materia->execute();
       $res_materia = $stmt_materia->get_result();
       if ($fila_materia = $res_materia->fetch_assoc()) {
           $materia_nombre = htmlspecialchars($fila_materia['nombre']);
       }
       $stmt_materia->close();
   }




   $topics = [];
   $sql_topics = "SELECT id_tema, nombre_tema FROM temas WHERE id_materia = ? ORDER BY nombre_tema ASC";
   $stmt_topics = $conexion->prepare($sql_topics);


   if ($stmt_topics) {
       $stmt_topics->bind_param("i", $materia_id);
       $stmt_topics->execute();
       $resultado_topics = $stmt_topics->get_result();
       while ($fila = $resultado_topics->fetch_assoc()) {
           $fila['nombre_tema'] = htmlspecialchars($fila['nombre_tema']);
           $topics[] = $fila;
       }
       $stmt_topics->close();
   } else {
   }
  
   $conexion->close();


   echo json_encode(['success' => true, 'materia_nombre' => $materia_nombre, 'topics' => $topics]);
   exit;


} else {
   echo json_encode(['error' => 'Acción no válida o no especificada.']);
   exit;
}
?>
