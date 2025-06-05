
<?php
session_start();
header('Content-Type: application/json');


$action = $_GET['action'] ?? '';


if ($action === 'get_admin_profile_data') {
   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado. Por favor, inicia sesión.', 'redirect' => 'login.html']);
       exit;
   }



   $servidor = "localhost";
   $usuario_db = "root";
   $password_db = "";
   $nombre_db = "pag"; 


   $conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);
   if ($conexion->connect_error) {
       echo json_encode(['error' => 'Error de conexión a la base de datos.']);
       exit;
   }
   $conexion->set_charset("utf8");


   $admin_id = $_SESSION['usuario_id'];
   $admin_nombre = "Admin"; 
   $admin_correo = "";     

   $sql = "SELECT nombre, correo FROM alta WHERE id = ? LIMIT 1";
   $stmt = $conexion->prepare($sql);


   if ($stmt) {
       $stmt->bind_param("i", $admin_id);
       $stmt->execute();
       $resultado = $stmt->get_result();
       if ($fila = $resultado->fetch_assoc()) {
           $admin_nombre = htmlspecialchars($fila['nombre']);
           $admin_correo = htmlspecialchars($fila['correo']);
       }
       $stmt->close();
   } else {
 }
   $conexion->close();


   echo json_encode([
       'success' => true,
       'message' => 'Datos del perfil obtenidos.',
       'admin_nombre' => $admin_nombre,
       'admin_correo' => $admin_correo
   ]);
   exit;


} else {
   echo json_encode(['error' => 'Acción no válida.']);
   exit;
}
?>

