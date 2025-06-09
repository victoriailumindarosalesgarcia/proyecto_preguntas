<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "pag";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST["topicName"]);
    $descripcion = $conn->real_escape_string($_POST["topicDescription"]);
    $materia_id = intval($_POST["materiaSelect"]);

    $sql = "INSERT INTO tema (nombre, descripcion, materia) VALUES ('$nombre', '$descripcion', $materia_id)";
    if ($conn->query($sql) === TRUE) {
    header("Location: temas_admin.php");
    exit();
} else {
    echo "<script>alert('Error al agregar tema: " . $conn->error . "');</script>";
}

    }


// Obtener materias
$materias = [];
$result = $conn->query("SELECT id_materia, nombre FROM materia");
while ($row = $result->fetch_assoc()) {
    $materias[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Tema</title>
    <link rel="stylesheet" href="add_tema.css">
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
                <div class="navbar-title-container">
                    <span class="navbar-title">Agregar Nuevo Tema</span>
                </div>
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
                <hr class="sidebar-divider">
                <li><a href="login.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="content-card">
            <h2>Nuevo Tema</h2>
            <form id="addTopicForm" method="POST" action="">
                <div class="form-section">
                    <label for="materiaSelect" class="form-label">Seleccionar Materia:</label>
                    <select id="materiaSelect" name="materiaSelect" class="form-input" required>
                        <option value="">-- Elige una materia --</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id_materia'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label for="topicName" class="form-label">Nombre del Tema:</label>
                    <input type="text" id="topicName" name="topicName" class="form-input" required>
                </div>
                
                <div class="form-section">
                    <label for="topicDescription" class="form-label">Descripción (Opcional):</label>
                    <textarea id="topicDescription" name="topicDescription" class="form-textarea"></textarea>
                </div>
                
                <div class="action-buttons">
                    <a href="temas_admin.php" class="button delete-button">Cancelar</a>
                    <button type="submit" class="button save-button">Guardar Tema</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Sidebar toggle
        document.getElementById("menuIcon").addEventListener("click", function() {
            document.body.classList.toggle("sidebar-open");
            document.getElementById("sidebar").classList.toggle("open");
        });
    </script>
</body>
</html>