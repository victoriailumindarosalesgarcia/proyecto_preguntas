<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $preguntaTexto = $_POST['pregunta'] ?? '';
    $dificultad = $_POST['dificultad'] ?? '';
    $correcta = $_POST['correcta'] ?? '';
    $id_user = 1;


    $preguntaImagenRuta = '';
    if (isset($_FILES['pregunta_imagen']) && $_FILES['pregunta_imagen']['error'] === UPLOAD_ERR_OK) {
        $preguntaImagenRuta = 'uploads/' . basename($_FILES['pregunta_imagen']['name']);
        move_uploaded_file($_FILES['pregunta_imagen']['tmp_name'], $preguntaImagenRuta);
    }


    $conexion = mysqli_connect("localhost", "root", "", "pag");
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }


    $stmt = mysqli_prepare($conexion, "INSERT INTO pregunta_opcion (texto, ruta_imagen, dificultad, id_user) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $preguntaTexto, $preguntaImagenRuta, $dificultad, $id_user);
    mysqli_stmt_execute($stmt);
    $id_pregunta = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);


    function insertar_opcion($conexion, $id_pregunta, $texto, $imagen, $es_correcta) {
        $stmt = mysqli_prepare($conexion, "INSERT INTO opciones (id_pregunta, texto, ruta_imagen, es_correcta) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issi", $id_pregunta, $texto, $imagen, $es_correcta);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    for ($i = 1; $i <= 4; $i++) {
        $texto = $_POST["op{$i}_texto"] ?? '';
        $es_correcta = ($correcta == $i) ? 1 : 0;


        $imagenRuta = '';
        if (isset($_FILES["op{$i}_imagen"]) && $_FILES["op{$i}_imagen"]['error'] === UPLOAD_ERR_OK) {
            $imagenRuta = 'uploads/' . basename($_FILES["op{$i}_imagen"]['name']);
            move_uploaded_file($_FILES["op{$i}_imagen"]['tmp_name'], $imagenRuta);
        }


        insertar_opcion($conexion, $id_pregunta, $texto, $imagenRuta, $es_correcta);
    }


    mysqli_close($conexion);
    header("Location: racha_profe.html");
    exit();
}
?>