<?php
$resultado = mysqli_stmt_get_result($stmt);


if ($fila = mysqli_fetch_assoc($resultado)) {
   // Verificar la contraseña encriptada
   if (password_verify($password, $fila['password'])) {
       $_SESSION['usuario_id'] = $fila['id_user'];
       $_SESSION['usuario_nombre'] = $fila['nombre'];


       header("Location: perfil_profe.html");
       exit;
   } else {
       echo "<h2>Contraseña incorrecta.</h2>";
   }
} else {
   echo "<h2>Usuario no encontrado.</h2>";
}


mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>


