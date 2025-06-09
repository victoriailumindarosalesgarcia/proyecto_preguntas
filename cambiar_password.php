<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        die("Error: Todos los campos son obligatorios. <a href='cambiar_password.html'>Volver</a>");
    }

    if ($new_password !== $confirm_password) {
        die("Error: La nueva contraseña y su confirmación no coinciden. <a href='cambiar_password.html'>Volver</a>");
    }

    $conexion = new mysqli("localhost", "root", "", "pag");
    if ($conexion->connect_error) { 
        die("Error de conexión a la base de datos: " . $conexion->connect_error); 
    }

    $stmt = $conexion->prepare("SELECT password FROM alta WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($current_password, $user['password'])) {

        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        
        $stmt_update = $conexion->prepare("UPDATE alta SET password = ? WHERE id_user = ?");
        $stmt_update->bind_param("si", $new_password_hashed, $id_user);
        
        if ($stmt_update->execute()) {
            header('Location: perfil_admin.html?success=password_changed');
        } else {
            die("Error al actualizar la contraseña en la base de datos. <a href='cambiar_password.html'>Volver</a>");
        }
        $stmt_update->close();
    } else {
        die("Error: La contraseña actual es incorrecta. <a href='cambiar_password.html'>Volver</a>");
    }
    
    $conexion->close();
} else {
    header('Location: cambiar_password.html');
    exit();
}
?>