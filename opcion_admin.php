
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




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_formulario']) && $_POST['accion_formulario'] === 'guardar_pregunta') {
    if (!isset($_SESSION['usuario_id'])) {
        die("Error: Sesión no válida.");
    }
 
    $conexion = new mysqli("localhost", "root", "", "pag");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $conexion->set_charset("utf8");
 
    // --- CAPTURAR DATOS ---
    $pregunta_texto = $_POST['pregunta'] ?? '';
    $dificultad = (int)($_POST['dificultad'] ?? 3);
    $opcion_correcta_idx = (int)($_POST['correcta'] ?? 0);
    $id_tema = !empty($_POST['id_tema']) ? (int)$_POST['id_tema'] : null; // <-- AÑADIDO
    $id_usuario_actual = $_SESSION['usuario_id'];
 
    // Validaciones
    if (empty($pregunta_texto) || $opcion_correcta_idx === 0 || $id_tema === null) {
         die("Error: Faltan datos obligatorios (pregunta, respuesta correcta o tema).");
    }
 
    $opciones_texto = [ /* ... */ ]; // Tu array de opciones
    
    $ruta_imagen_pregunta = null; // Tu lógica de subida de imagen
 
    $conexion->begin_transaction();
 
    try {
        // --- GUARDAR PREGUNTA ---
        $sql_pregunta = "INSERT INTO pregunta_opcion (texto, ruta_imagen, dificultad, id_user, id_tema) VALUES (?, ?, ?, ?, ?)";
        $stmt_pregunta = $conexion->prepare($sql_pregunta);
        if (!$stmt_pregunta) {
            throw new Exception("Error preparando la consulta: " . $conexion->error);
        }
        $stmt_pregunta->bind_param("ssiii", $pregunta_texto, $ruta_imagen_pregunta, $dificultad, $id_usuario_actual, $id_tema);
       
        if (!$stmt_pregunta->execute()) {
            throw new Exception("Error al guardar la pregunta: " . $stmt_pregunta->error);
        }
        $id_pregunta_insertada = $stmt_pregunta->insert_id;
        $stmt_pregunta->close();
 
        // --- GUARDAR OPCIONES ---
        // ... (tu bucle para guardar las opciones no cambia) ...
 
        $conexion->commit();
        header('Location: preguntas_admin.html');
        exit;
 
    } catch (Exception $e) {
        $conexion->rollback();
        die("Error al guardar la pregunta: " . $e->getMessage());
    } finally {
        $conexion->close();
    }
 }
}

?>
