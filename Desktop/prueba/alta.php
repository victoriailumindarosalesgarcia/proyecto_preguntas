<?php

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "pag");
header('Content-Type: application/json');

if ($conexion->connect_error) {
   die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? 2; // Por defecto, profesor

// Validación básica
if (empty($nombre) || empty($correo) || empty($password)) {
   echo "Por favor completa todos los campos.";
   exit;
}

// Validar tipo de usuario (solo 1 o 2)
if (!in_array($tipo_usuario, ['1', '2'])) {
   echo "Tipo de usuario inválido.";
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
$sql_insert = "INSERT INTO alta (nombre, correo, password, tipo_usuario) VALUES (?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("sssi", $nombre, $correo, $password_segura, $tipo_usuario);

if ($stmt_insert->execute()) {
   header("Location: dashboard_admin.html");
   exit;
} else {
   echo "Error al registrar: " . $stmt_insert->error;
}

if (!in_array($tipo_usuario, ['1', '2'])) {
   echo "Por favor selecciona un tipo de usuario válido.";
   exit;
}

$stmt_insert->close();
$conexion->close();
?>
