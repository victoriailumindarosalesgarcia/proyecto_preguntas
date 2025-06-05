<?php
session_start();
header('Content-Type: application/json'); // Indicar que la respuesta será JSON


// Verificar si la acción es 'check_session' (para más claridad si este PHP manejara más acciones)
$action = $_GET['action'] ?? '';


if ($action === 'check_session') {
   if (!isset($_SESSION['usuario_id'])) {
       // Si no hay sesión, devolver un error y una indicación para redirigir
       echo json_encode(['error' => 'Acceso no autorizado. Por favor, inicia sesión.', 'redirect' => 'login.html']);
       exit;
   } else {
       // Sesión válida
       echo json_encode(['success' => true, 'message' => 'Sesión válida.']);
       exit;
   }
} else {
   // Acción no reconocida o no proporcionada
   echo json_encode(['error' => 'Acción no válida.']);
   exit;
}
?>
