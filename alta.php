<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "pag");


if ($conexion->connect_error) {
   die("Error de conexión: " . $conexion->connect_error);
}


// Obtener los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';


// Validación básica
if (empty($nombre) || empty($correo) || empty($password)) {
   echo "Por favor completa todos los campos.";
   exit;
}


// Verificar si el correo ya está registrado
$sql_check = "SELECT id_user FROM alta WHERE correo = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->store_result();


if ($stmt_check->num_rows > 0) {
   echo "Este correo ya está registrado. Intenta con otro.";
   $stmt_check->close();
   $conexion->close();
   exit;
}
$stmt_check->close();


// Encriptar la contraseña
$password_segura = password_hash($password, PASSWORD_BCRYPT);


// Insertar nuevo usuario
$sql_insert = "INSERT INTO alta (nombre, correo, password) VALUES (?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("sss", $nombre, $correo, $password_segura);


if ($stmt_insert->execute()) {
   echo "<h2>Registro exitoso. ¡Bienvenido, $nombre!</h2>";
} else {
   echo "Error al registrar: " . $stmt_insert->error;
}


$stmt_insert->close();
$conexion->close();
?>
