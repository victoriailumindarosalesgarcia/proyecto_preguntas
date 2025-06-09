<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 

$conexion = new mysqli("localhost", "root", "", "pag");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        $id = intval($_POST['id']);
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        $correo = $conexion->real_escape_string($_POST['correo']);
        $tipo_usuario = intval($_POST['tipo_usuario']);

        $conexion->query("UPDATE alta SET nombre='$nombre', correo='$correo', tipo_usuario=$tipo_usuario WHERE id_user=$id");
    }

    if (isset($_POST['eliminar'])) {
        $id = intval($_POST['id']);

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id'] != $id) {
            // Primero eliminamos los datos relacionados
            $conexion->query("DELETE FROM opciones WHERE id_pregunta IN (
                SELECT id_preg_op FROM pregunta_opcion WHERE id_user = $id
            )");
            $conexion->query("DELETE FROM pregunta_opcion WHERE id_user = $id");

            // Opción múltiple
            $conexion->query("DELETE FROM opciones WHERE id_pregunta IN (
                SELECT id_preg_op FROM pregunta_opcion WHERE id_user = $id
            )");
            $conexion->query("DELETE FROM pregunta_opcion WHERE id_user = $id");

            // Verdadero/falso
            $conexion->query("DELETE FROM pregunta_vf WHERE id_user = $id");

            // Preguntas abiertas
            $conexion->query("DELETE FROM pregunta_abierta WHERE id_user = $id");

            // Finalmente, eliminamos al usuario
            $conexion->query("DELETE FROM alta WHERE id_user = $id");
        }
    }

    header("Location: admin_usuarios.php");
    exit();
}

$usuarios = $conexion->query("SELECT * FROM alta");
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="admin_usuarios.css">
    <script src="admin_usuarios.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
   <header class="navbar">
    <div class="navbar">
        <div class="navbar-left">
            <div class="menu-icon" id="menuIcon">☰</div>
            <a href="dashboard_admin.html" id="homeIcon">
                <i class="fas fa-home home-icon"></i>
            </a>
        </div>

       <div class="navbar-title-container">
            <span class="navbar-title">Gestión de Usuarios</span>
       </div>
    </div>
   </header>

  <aside class="sidebar" id="sidebar">
    <nav>
      <ul>
        <li><a href="dashboard_admin.html">Inicio</a></li>
        <li><a href="perfil_admin.html">Mi Perfil</a></li>
        <li><a href="materias_admin.html">Materias</a></li>
        <li><a href="temas_admin.html">Temas</a></li>
        <li><a href="preguntas_admin.html">Preguntas</a></li>
        <li><a href="admin_usuarios.html">Gestionar usuarios</a></li>
        <li><a href="alta.html">Alta de Usuario</a></li>
        <hr class="sidebar-divider">
        <li><a href="login.html">Cerrar Sesión</a></li>
      </ul>
    </nav>
  </aside>

<div class="login-card">
    <h2>Usuarios registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
<?php if ($usuarios && $usuarios->num_rows > 0): ?>
    <?php while ($u = $usuarios->fetch_assoc()): ?>
        <tr>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $u['id_user'] ?>"> <!-- Campo oculto con el ID correcto -->
                <td><?= $u['id_user'] ?></td> <!-- Puedes dejarlo visible si lo deseas -->
                <td><input type="text" name="nombre" value="<?= htmlspecialchars($u['nombre']) ?>"></td>
                <td><input type="email" name="correo" value="<?= htmlspecialchars($u['correo']) ?>"></td>
                <td>
                    <select name="tipo_usuario" class="rol-selector">
                        <option value="1" <?= $u['tipo_usuario'] == 1 ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= $u['tipo_usuario'] == 2 ? 'selected' : '' ?>>Profesor</option>
                    </select>
                </td>
                <td class="action-buttons">
                    <button type="submit" name="editar" class="btn-guardar">Guardar</button>
                    <button type="submit" name="eliminar" class="btn-eliminar" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                </td>
            </form>
        </tr>
            <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="5">No hay usuarios registrados.</td></tr>
<?php endif; ?>
</tbody>

    </table>
</div>
</body>
</html>