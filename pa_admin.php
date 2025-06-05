<?php
session_start();


$directorio_subidas = "uploads/"; 

function manejarSubidaArchivo($file_input_name, $directorio_subidas) {
   if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
       $archivo_temporal = $_FILES[$file_input_name]['tmp_name'];
       $nombre_archivo = uniqid() . '-' . basename($_FILES[$file_input_name]['name']);
       $ruta_destino = $directorio_subidas . $nombre_archivo;
       $tipo_archivo = strtolower(pathinfo($ruta_destino, PATHINFO_EXTENSION));
       $extensiones_permitidas = array("jpg", "jpeg", "png", "gif");


       if (!in_array($tipo_archivo, $extensiones_permitidas)) {
           return ['error' => "Error: Solo se permiten archivos JPG, JPEG, PNG y GIF para '".$file_input_name."'."];
       }
       if ($_FILES[$file_input_name]['size'] > 2 * 1024 * 1024) { // Max 2MB
           return ['error' => "Error: El archivo '".$file_input_name."' es demasiado grande (máximo 2MB)."];
       }
       if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
           return ['success' => true, 'ruta_archivo' => $ruta_destino];
       } else {
           return ['error' => "Error al mover el archivo subido '".$file_input_name."'."];
       }
   } elseif (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] != UPLOAD_ERR_NO_FILE) {
       return ['error' => "Error en la subida del archivo '".$file_input_name."': código " . $_FILES[$file_input_name]['error']];
   }
   return ['success' => true, 'ruta_archivo' => null]; 
}


// Manejo de Solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check_session') {
   header('Content-Type: application/json');
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado.', 'redirect' => 'login.html']);
   } else {
       echo json_encode(['success' => true, 'message' => 'Sesión válida.']);
   }
   exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_formulario']) && $_POST['accion_formulario'] === 'guardar_pregunta_abierta') {
   if (!isset($_SESSION['usuario_id'])) {
       $_SESSION['mensaje_error'] = "Error: Sesión no válida o expirada.";
       header('Location: pa_admin.html');
       exit;
   }


   $servidor = "localhost"; $usuario_db = "root"; $password_db = ""; $nombre_db = "pag";
   $conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
   if ($conexion->connect_error) {
       $_SESSION['mensaje_error'] = "Error de conexión a la base de datos.";
       header('Location: pa_admin.html');
       exit;
   }
   $conexion->set_charset("utf8");



   $pregunta_texto = trim($_POST['pregunta_texto'] ?? '');
   $respuesta_esperada = trim($_POST['respuesta_esperada'] ?? '');
   $dificultad = (int)($_POST['dificultad'] ?? 3);
   $id_usuario_actual = $_SESSION['usuario_id'];
  
   $estado_pregunta = 'pendiente'; 
   if (isset($_POST['aprobar_btn'])) {
       
       $estado_pregunta = 'aprobada';
   }


  
   if (empty($pregunta_texto) || empty($respuesta_esperada)) {
       $_SESSION['mensaje_error'] = "Error: El texto de la pregunta y la respuesta esperada son obligatorios.";
       header('Location: pa_admin.html');
       exit;
   }


   $ruta_imagen_pregunta = null;
   $resultado_subida_pregunta = manejarSubidaArchivo('pregunta_imagen', $directorio_subidas);
   if (isset($resultado_subida_pregunta['error'])) {
       $_SESSION['mensaje_error'] = $resultado_subida_pregunta['error'];
       header('Location: pa_admin.html');
       exit;
   }
   $ruta_imagen_pregunta = $resultado_subida_pregunta['ruta_archivo'];


   $conexion->begin_transaction();
   try {

       $sql_insert = "INSERT INTO preguntas_abiertas (texto_pregunta, ruta_imagen_pregunta, respuesta_esperada, dificultad, id_usuario, estado) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt = $conexion->prepare($sql_insert);
       if (!$stmt) {
           throw new Exception("Error preparando la consulta: " . $conexion->error);
       }
      
       $stmt->bind_param("sssiis", $pregunta_texto, $ruta_imagen_pregunta, $respuesta_esperada, $dificultad, $id_usuario_actual, $estado_pregunta);
      
       if (!$stmt->execute()) {
           throw new Exception("Error al guardar la pregunta abierta: " . $stmt->error);
       }
       $stmt->close();
       $conexion->commit();
      
       $_SESSION['mensaje_exito'] = "¡Pregunta abierta guardada exitosamente con estado: ".$estado_pregunta."!";
       header('Location: pa_admin.html'); // Redirigir para limpiar el form
       exit;


   } catch (Exception $e) {
       $conexion->rollback();
       $_SESSION['mensaje_error'] = "Error al guardar la pregunta: " . $e->getMessage();
       header('Location: pa_admin.html');
       exit;
   } finally {
       $conexion->close();
   }
}
?>

