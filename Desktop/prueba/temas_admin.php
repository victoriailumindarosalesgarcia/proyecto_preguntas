<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conexion = new mysqli("localhost", "root", "", "pag");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Editar tema
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        $id = intval($_POST['id']);
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        $descripcion = $conexion->real_escape_string($_POST['descripcion']);
        $materia = intval($_POST['materia']);

        $conexion->query("UPDATE tema SET nombre='$nombre', descripcion='$descripcion', materia=$materia WHERE id_tema=$id");
    }

    if (isset($_POST['eliminar'])) {
        $id = intval($_POST['id']);
        $conexion->query("DELETE FROM tema WHERE id_tema=$id");
    }

    header("Location: temas_admin.php");
    exit();
}

// Obtener temas con nombre de materia
$temas = $conexion->query("
    SELECT tema.id_tema, tema.nombre AS nombre_tema, tema.descripcion, materia.nombre AS nombre_materia, materia.id_materia
    FROM tema
    INNER JOIN materia ON tema.materia = materia.id_materia
");

// Obtener todas las materias para el selector
$materias = $conexion->query("SELECT id_materia, nombre FROM materia");
$materias_array = [];
while ($m = $materias->fetch_assoc()) {
    $materias_array[$m['id_materia']] = $m['nombre'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Temas</title>
    <link rel="stylesheet" href="admin_usuarios.css">
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
            <span class="navbar-title">Gestión de Temas</span>
        </div>
    </div>
</header>

<aside class="sidebar" id="sidebar">
    <nav>
        <ul>
            <li><a href="dashboard_admin.html">Inicio</a></li>
            <li><a href="perfil_admin.html">Mi Perfil</a></li>
            <li><a href="materias_admin.html">Materias</a></li>
            <li><a href="temas_admin.php">Temas</a></li>
            <li><a href="preguntas_admin.html">Preguntas</a></li>
            <li><a href="admin_usuarios.html">Gestionar usuarios</a></li>
            <li><a href="alta.html">Alta de Usuario</a></li>
            <hr class="sidebar-divider">
            <li><a href="login.html">Cerrar Sesión</a></li>
        </ul>
    </nav>
</aside>

<div class="login-card">
    <h2>Temas Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Tema</th>
                <th>Descripción</th>
                <th>Materia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($temas && $temas->num_rows > 0): ?>
            <?php while ($t = $temas->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $t['id_tema'] ?>">
                        <td><?= $t['id_tema'] ?></td>
                        <td><input type="text" name="nombre" value="<?= htmlspecialchars($t['nombre_tema']) ?>"></td>
                        <td><input type="text" name="descripcion" value="<?= htmlspecialchars($t['descripcion']) ?>"></td>
                        <td>
                            <select name="materia">
                                <?php foreach ($materias_array as $id => $nombre): ?>
                                    <option value="<?= $id ?>" <?= $id == $t['id_materia'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="action-buttons">
                            <button type="submit" name="editar" class="btn-guardar">Guardar</button>
                            <button type="submit" name="eliminar" class="btn-eliminar" onclick="return confirm('¿Eliminar este tema?')">Eliminar</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No hay temas registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById("menuIcon").addEventListener("click", function () {
        document.getElementById("sidebar").classList.toggle("open");
    });
</script>
</body>
</html>