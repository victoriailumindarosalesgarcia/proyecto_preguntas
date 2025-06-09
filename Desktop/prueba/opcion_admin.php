
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

       if ($_FILES[$file_input_name]['size'] > 2 * 1024 * 1024) {
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




if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check_session') {
   header('Content-Type: application/json');
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado.', 'redirect' => 'login.html']);
   } else {
       echo json_encode(['success' => true, 'message' => 'Sesión válida.']);
   }
   exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_formulario']) && $_POST['accion_formulario'] === 'guardar_pregunta') {
   if (!isset($_SESSION['usuario_id'])) {
     
       die("Error: Sesión no válida o expirada. Por favor, inicie sesión nuevamente.");
   }
   $servidor = "localhost";
   $usuario_db = "root";
   $password_db = "";
   $nombre_db = "pag";


   $conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
   if ($conexion->connect_error) {
       die("Error de conexión a la base de datos: " . $conexion->connect_error);
   }
   $conexion->set_charset("utf8");

   $pregunta_texto = $_POST['pregunta'] ?? '';
   $dificultad = (int)($_POST['dificultad'] ?? 3);
   $opcion_correcta_idx = (int)($_POST['correcta'] ?? 1);


   $opciones_texto = [
       1 => $_POST['op1_texto'] ?? '',
       2 => $_POST['op2_texto'] ?? '',
       3 => $_POST['op3_texto'] ?? null, 
       4 => $_POST['op4_texto'] ?? null 
   ];
  
   $id_usuario_actual = $_SESSION['usuario_id']; 

   $ruta_imagen_pregunta = null;
   $resultado_subida_pregunta = manejarSubidaArchivo('pregunta_imagen', $directorio_subidas);
   if (isset($resultado_subida_pregunta['error'])) {
       die($resultado_subida_pregunta['error']); 
   }
   $ruta_imagen_pregunta = $resultado_subida_pregunta['ruta_archivo'];


   $conexion->begin_transaction();


   try {
       $sql_pregunta = "INSERT INTO preguntas (texto, ruta_imagen, dificultad, id_usuario) VALUES (?, ?, ?, ?)";
       $stmt_pregunta = $conexion->prepare($sql_pregunta);
       if (!$stmt_pregunta) {
           throw new Exception("Error preparando la consulta de pregunta: " . $conexion->error);
       }
       $stmt_pregunta->bind_param("ssii", $pregunta_texto, $ruta_imagen_pregunta, $dificultad, $id_usuario_actual);
      
       if (!$stmt_pregunta->execute()) {
           throw new Exception("Error al guardar la pregunta: " . $stmt_pregunta->error);
       }
       $id_pregunta_insertada = $stmt_pregunta->insert_id;
       $stmt_pregunta->close();


       $sql_opcion = "INSERT INTO opciones (id_pregunta, texto, ruta_imagen, es_correcta) VALUES (?, ?, ?, ?)";
       $stmt_opcion = $conexion->prepare($sql_opcion);
       if (!$stmt_opcion) {
           throw new Exception("Error preparando la consulta de opción: " . $conexion->error);
       }


       for ($i = 1; $i <= 4; $i++) {
           if (!empty($opciones_texto[$i])) { 
               $texto_op = $opciones_texto[$i];
               $es_correcta = ($i == $opcion_correcta_idx) ? 1 : 0;
              
               $ruta_imagen_opcion = null;
               $resultado_subida_opcion = manejarSubidaArchivo('op'.$i.'_imagen', $directorio_subidas);
               if (isset($resultado_subida_opcion['error'])) {
        
                   throw new Exception($resultado_subida_opcion['error']);
               }
               $ruta_imagen_opcion = $resultado_subida_opcion['ruta_archivo'];


               $stmt_opcion->bind_param("issi", $id_pregunta_insertada, $texto_op, $ruta_imagen_opcion, $es_correcta);
               if (!$stmt_opcion->execute()) {
                   throw new Exception("Error al guardar la opción ".$i.": " . $stmt_opcion->error);
               }
           }
       }
       $stmt_opcion->close();

       $conexion->commit();
      
       $_SESSION['mensaje_exito'] = "¡Pregunta guardada exitosamente!";
       header('Location: preguntas_admin.php'); // Redirigir a la misma página para limpiar el form (o a una lista de preguntas)
       exit;


   } catch (Exception $e) {
       $conexion->rollback();
       $_SESSION['mensaje_error'] = "Error al guardar la pregunta: " . $e->getMessage();
       header('Location: opcion_admin.html'); 
       exit;
   } finally {
       $conexion->close();
   }
}

?>
