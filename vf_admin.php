<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar sesión
    if (!isset($_SESSION['usuario_id'])) {
        die("Error: Sesión no válida. Por favor, inicia sesión.");
    }

    // Recoger y validar datos del formulario
    $pregunta = trim($_POST['pregunta_texto'] ?? '');
    $respuesta = $_POST['respuesta_correcta_vf'] ?? '';
    $dificultad = intval($_POST['dificultad'] ?? 3);
    $id_tema = !empty($_POST['id_tema']) ? intval($_POST['id_tema']) : null;
    $id_user = $_SESSION['usuario_id'];
    $accion = $_POST['accion_formulario'] ?? '';

    // Determinar el estado basado en el botón presionado
    $estado = "pendiente";
    if (isset($_POST['aprobar_btn'])) {
        $estado = 'aprobada';
    }

    if ($accion === "guardar_pregunta_vf" && !empty($pregunta) && !empty($respuesta) && $id_tema !== null) {
        $conexion = mysqli_connect("localhost", "root", "", "pag");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        mysqli_set_charset($conexion, "utf8");

        // Manejo de imagen (guardar como ruta)
        $ruta_imagen = null;
        if (isset($_FILES['pregunta_imagen']) && $_FILES['pregunta_imagen']['error'] == UPLOAD_ERR_OK) {
            $directorio_subidas = "uploads/";
            if (!is_dir($directorio_subidas)) {
                mkdir($directorio_subidas, 0755, true);
            }
            $nombre_archivo = uniqid() . '-' . basename($_FILES['pregunta_imagen']['name']);
            $ruta_destino = $directorio_subidas . $nombre_archivo;
            if (move_uploaded_file($_FILES['pregunta_imagen']['tmp_name'], $ruta_destino)) {
                $ruta_imagen = $ruta_destino;
            }
        }

        $incorrecta = ($respuesta === "verdadero") ? "falso" : "verdadero";

        $stmt = mysqli_prepare($conexion, "INSERT INTO pregunta_vf (texto, imagen, respuesta, incorrecta, dificultad, estado, id_user, id_tema) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error al preparar la consulta: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "ssssisii", $pregunta, $ruta_imagen, $respuesta, $incorrecta, $dificultad, $estado, $id_user, $id_tema);
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            header("Location: preguntas_admin.html"); // Redirigir a una página de éxito
            exit();
        } else {
            die("Error al guardar la pregunta: " . mysqli_stmt_error($stmt));
        }
    } else {
        echo "Error: Faltan datos obligatorios o la acción es inválida.";
        echo '<br><a href="vf_admin.html">Volver al editor</a>';
    }
}
?>