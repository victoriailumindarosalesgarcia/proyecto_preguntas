<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conexion = new mysqli("localhost", "root", "", "pag");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Asegurarse de que se haya proporcionado el ID de la materia
$id_materia = isset($_GET['id_materia']) ? intval($_GET['id_materia']) : 0;

// Manejo de edición o eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        $id_tema = intval($_POST['id_tema']);
        $nuevo_nombre = $conexion->real_escape_string($_POST['nombre_tema']);
        $conexion->query("UPDATE tema SET nombre_tema='$nuevo_nombre' WHERE id_tema=$id_tema");
    }

    if (isset($_POST['eliminar'])) {
        $id_tema = intval($_POST['id_tema']);
        // Elimina primero las preguntas asociadas si es necesario
        $conexion->query("DELETE FROM pregunta WHERE id_tema = $id_tema");
        $conexion->query("DELETE FROM tema WHERE id_tema = $id_tema");
    }

    header("Location: temas_admin.php?id_materia=$id_materia");
    exit();
}

$temas = $conexion->query("SELECT * FROM tema WHERE id_materia = $id_materia");
$materia_nombre = "Materia desconocida";
$result_materia = $conexion->query("SELECT nombre_materia FROM materia WHERE id_materia = $id_materia");
if ($row = $result_materia->fetch_assoc()) {
    $materia_nombre = $row['nombre_materia'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Administrar Temas</title>
    <link rel="stylesheet" href="temas_admin.css" />
</head>
<body>
    <header class="navbar">
        <div class="menu-icon">&#9776;</div>
        <div class="navbar-title-container">
            <span class="navbar-title">Administración de Temas</span>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard_admin.html">Inicio</a></li>
                <li><a href="perfil_admin.html">Mi Perfil</a></li>
                <li><a href="materias_admin.html">Materias</a></li>
                <li><a href="temas_admin.php?id_materia=<?= $id_materia ?>">Temas</a></li>
                <li><a href="preguntas_admin.html">Preguntas</a></li>
                <hr class="sidebar-divider">
                <li><a href="login.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="content-card">
            <h2>Temas de: <?= htmlspecialchars($materia_nombre) ?></h2>

            <div class="add-item-container">
                <a href="add_tema.php?id_materia=<?= $id_materia ?>" class="button add-new-item-button">&#10133; Añadir Nuevo Tema</a>
            </div>

            <table class="tema-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Tema</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($temas && $temas->num_rows > 0): ?>
                        <?php while ($t = $temas->fetch_assoc()): ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="id_tema" value="<?= $t['id_tema'] ?>">
                                    <td><?= $t['id_tema'] ?></td>
                                    <td><input type="text" name="nombre_tema" value="<?= htmlspecialchars($t['nombre_tema']) ?>"></td>
                                    <td class="action-buttons">
                                        <button type="submit" name="editar" class="btn-guardar">Guardar</button>
                                        <button type="submit" name="eliminar" class="btn-eliminar" onclick="return confirm('¿Eliminar este tema?')">Eliminar</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No hay temas registrados para esta materia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <a href="materias_admin.html" class="back-button">Volver a Materias</a>
    </main>
</body>
</html>
