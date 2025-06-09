<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['usuario_id'];
    $nuevo_nombre = trim($_POST['nuevo_nombre'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nuevo_nombre) || empty($password)) {
        die("Todos los campos son obligatorios.");
    }

    $conexion = new mysqli("localhost", "root", "", "pag");
    if ($conexion->connect_error) { die("Error de conexión"); }

    $stmt = $conexion->prepare("SELECT password FROM alta WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $stmt_update = $conexion->prepare("UPDATE alta SET nombre = ? WHERE id_user = ?");
        $stmt_update->bind_param("si", $nuevo_nombre, $id_user);
        if ($stmt_update->execute()) {
            $_SESSION['nombre'] = $nuevo_nombre; // Actualizar sesión
            header('Location: perfil_admin.html?success=name');
        } else {
            header('Location: cambiar_nombre.html?error=db');
        }
        $stmt_update->close();
    } else {
        header('Location: cambiar_nombre.html?error=password');
    }
    $conexion->close();
}
?>