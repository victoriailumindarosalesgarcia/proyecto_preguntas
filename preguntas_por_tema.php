<?php
$id_tema = isset($_GET['id_tema']) ? intval($_GET['id_tema']) : 0;
if ($id_tema === 0) {
    die("ID de tema no válido.");
}

$conexion = new mysqli("localhost", "root", "", "pag");
if ($conexion->connect_error) { die("Error de conexión"); }
$conexion->set_charset("utf8");

// Obtener información del tema y la materia
$stmt_info = $conexion->prepare("SELECT t.nombre AS nombre_tema, m.nombre AS nombre_materia, m.id_materia FROM tema t JOIN materia m ON t.materia = m.id_materia WHERE t.id_tema = ?");
$stmt_info->bind_param("i", $id_tema);
$stmt_info->execute();
$info = $stmt_info->get_result()->fetch_assoc();
$nombre_tema = $info['nombre_tema'] ?? 'Desconocido';
$nombre_materia = $info['nombre_materia'] ?? 'Desconocida';
$id_materia = $info['id_materia'] ?? 0;
$stmt_info->close();

// Obtener todas las preguntas para ese tema
$preguntas = [];
$sql = "(SELECT id_preg_op as id, texto, 'Opción Múltiple' as tipo FROM pregunta_opcion WHERE id_tema = ?)
        UNION ALL
        (SELECT id_pregunta as id, texto, 'Verdadero/Falso' as tipo FROM pregunta_vf WHERE id_tema = ?)
        UNION ALL
        (SELECT id_pregunta as id, texto, 'Abierta' as tipo FROM pregunta_abierta WHERE id_tema = ?)";
$stmt_preguntas = $conexion->prepare($sql);
$stmt_preguntas->bind_param("iii", $id_tema, $id_tema, $id_tema);
$stmt_preguntas->execute();
$result_preguntas = $stmt_preguntas->get_result();
while ($row = $result_preguntas->fetch_assoc()) {
    $preguntas[] = $row;
}
$stmt_preguntas->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Preguntas de <?= htmlspecialchars($nombre_tema) ?></title>
    <link rel="stylesheet" href="preguntas_admin.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-left">
            <div class="menu-icon" id="menuIcon">☰</div>
            <a href="dashboard_admin.html" id="homeIcon"><i class="fas fa-home home-icon"></i></a>
        </div>
        <div class="navbar-title-container"><span class="navbar-title">Preguntas por Tema</span></div>
    </header>

    <aside class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard_admin.html">Inicio</a></li>
                <li><a href="perfil_admin.html">Mi Perfil</a></li>
                <li><a href="materias_admin.html">Materias</a></li>
                <li><a href="temas_admin.php">Temas</a></li>
                <li><a href="preguntas_admin.html">Preguntas</a></li>
                <hr class="sidebar-divider">
                <li><a href="login.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="content-card">
            <div class="header-actions">
                <h2>Tema: <?= htmlspecialchars($nombre_tema) ?> <span style="font-weight: normal; color: #555; font-size: 1rem;">(Materia: <?= htmlspecialchars($nombre_materia) ?>)</span></h2>
                <a href="question_type_selection.html" class="button add-button">+ Nueva Pregunta</a>
            </div>
            <div class="table-container">
                <table id="questionsTable">
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($preguntas) > 0): ?>
                            <?php foreach($preguntas as $pregunta): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pregunta['texto']) ?></td>
                                    <td><?= htmlspecialchars($pregunta['tipo']) ?></td>
                                    <td class="action-buttons">
                                        <button class="edit-btn">Editar</button>
                                        <button class="delete-btn">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align: center;">No hay preguntas para este tema.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="temas_admin.php?materia_id=<?= $id_materia ?>" class="back-button">Volver a la lista de temas</a>
    </main>
    <script>
        // Lógica del menú lateral
        document.getElementById("menuIcon").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("open");
            document.body.classList.toggle("sidebar-open");
        });
    </script>
</body>
</html>