<?php
session_start();
header('Content-Type: application/json');


$response = ['loggedIn' => false];


if (isset($_GET['action']) && $_GET['action'] === 'check_if_logged_in') {
   if (isset($_SESSION['usuario_id'])) {
       $response['loggedIn'] = true;

       if (isset($_SESSION['rol_usuario'])) { 
           switch ($_SESSION['rol_usuario']) {
               case 'admin':
                   $response['redirect_url'] = 'materias_admin.html'; 
                   break;
               case 'profesor':
                   $response['redirect_url'] = 'materias_profe.html'; 
                   break;
               default:
                   $response['redirect_url'] = 'dashboard_general.html';
                   break;
           }
       } else {
           $response['redirect_url'] = 'materias_admin.html'; 
       }
   }
} else {

}


echo json_encode($response);
exit;
?>
