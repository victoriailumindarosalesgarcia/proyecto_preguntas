<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST['pregunta_texto'] ?? '';
    $respuesta = $_POST['respuesta_esperada'] ?? '';
    $dificultad = $_POST['dificultad'] ?? '';
    $accion = $_POST['accion_formulario'] ?? '';


    if ($accion === "guardar_pregunta_abierta_profe") {
        $conexion = mysqli_connect("localhost", "root", "", "pag");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }


    
        $imagen = null;
        if (isset($_FILES['pregunta_imagen']) && $_FILES['pregunta_imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenTmp = $_FILES['pregunta_imagen']['tmp_name'];
            $imagen = file_get_contents($imagenTmp);  
        }

        $estado = "borrador";


        $stmt = mysqli_prepare($conexion, "INSERT INTO pregunta_abierta (texto, imagen, respuesta, dificultad, estado) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssis", $pregunta, $imagen, $respuesta, $dificultad, $estado);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);


        echo "Pregunta guardada correctamente.";
        header("Location: racha_profe.php");  
        exit();


    } elseif ($accion === "eliminar") {
        echo "No se guardó la pregunta.";
        echo '<br><a href="pa_profe.html">Volver al editor</a>';
    }
}
?>