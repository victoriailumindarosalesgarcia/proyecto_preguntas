<?php
session_start();
header('Content-Type: application/json');


$action = $_GET['action'] ?? '';


if ($action === 'check_session') {

   if (!isset($_SESSION['usuario_id'])) {
       echo json_encode(['error' => 'Acceso no autorizado. Por favor, inicia sesión.', 'redirect' => 'login.html']);
       exit;
   } else {
       echo json_encode(['success' => true, 'message' => 'Sesión válida.']);
       exit;
   }
} else {
   // Acción no reconocida
   echo json_encode(['error' => 'Acción no válida.']);
   exit;
}
?>




