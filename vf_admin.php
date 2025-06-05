<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST['pregunta_texto'] ?? '';
    $respuesta = $_POST['respuesta_correcta_vf'] ?? '';
    $dificultad = intval($_POST['dificultad'] ?? 3); 
    $estado = "Borrador";
    $accion = $_POST['accion_formulario'] ?? '';
    $imagen_url = $_POST['pregunta_imagen_url'] ?? null;
    $id_user = $_SESSION['id_user'] ?? null;



    if ($accion === "guardar_pregunta_vf_profe") {
        $conexion = mysqli_connect("localhost", "root", "", "pag");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        $incorrecta = ($respuesta === "verdadero") ? "falso" : "verdadero";

        $stmt = mysqli_prepare($conexion, "INSERT INTO pregunta_vf (texto, imagen, respuesta, incorrecta, dificultad, estado, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error al preparar la consulta: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "ssssisi", $pregunta, $imagen_url, $respuesta, $incorrecta, $dificultad, $estado, $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        header("Location: preguntas_admin.html");
        exit();
    } else {
        echo "Acción inválida.";
        echo '<br><a href="vf_admin.html">Volver al editor</a>';
    }
}
?>
