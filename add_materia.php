<?php
// --- 1. Conexión a la base de datos ---
$host = "localhost";
$user = "root";
$password = ""; // Cambia esto si tu MySQL tiene contraseña
$dbname = "pag";

// Crear conexión
$conn = new mysqli("localhost", "root", "", "pag");;

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// --- 2. Obtener datos del formulario ---
$nombre = isset($_POST['topicName']) ? trim($_POST['topicName']) : '';
$descripcion = isset($_POST['topicDescription']) ? trim($_POST['topicDescription']) : '';

// --- 3. Validación básica ---
if (empty($nombre)) {
    die("El nombre de la materia es obligatorio.");
}

// --- 4. Preparar e insertar datos ---
$stmt = $conn->prepare("INSERT INTO materia (nombre, descripcion) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $descripcion);

if ($stmt->execute()) {
    // Éxito: Redirigir o retornar mensaje
    header("Location: materias_admin.php?success=1");
    exit();
} else {
    echo "Error al insertar: " . $stmt->error;
}

// --- 5. Cerrar conexión ---
$stmt->close();
$conn->close();
?>