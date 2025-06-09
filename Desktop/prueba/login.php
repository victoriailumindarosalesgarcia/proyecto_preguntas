<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion = new mysqli("localhost", "root", "", "pag");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($correo) || empty($password)) {
    echo "Por favor completa todos los campos.";
    exit;
}

$sql = "SELECT id_user, password, tipo_usuario FROM alta WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($id_user, $hash_guardado, $tipo_usuario);
    $stmt->fetch();

    if (password_verify($password, $hash_guardado)) {
        // Redirigir dependiendo del tipo de usuario
        if ($tipo_usuario == 1) {
            header("Location: dashboard_admin.html");
        } else if ($tipo_usuario == 2) {
            header("Location: dashboard_profe.html");
        } else {
            echo "Tipo de usuario no reconocido.";
        }
        exit;
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

$stmt->close();
$conexion->close();
?>
