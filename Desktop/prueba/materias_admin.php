<?php
// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión
$conn = new mysqli("localhost", "root", "", "pag");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT id_materia, nombre FROM materia";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Materias</title>
    <link rel="stylesheet" href="materias_admin.css"> <!-- Asegúrate que este archivo exista -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <div class="menu-icon" id="menuIcon">☰</div>
            <a href="dashboard_admin.html" id="homeIcon">
                <i class="fas fa-home home-icon"></i>
            </a>
        </div>
            <div class="navbar-title-container">
                <span class="navbar-title">Materias</span>
            </div>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard_admin.html">Inicio</a></li>
                <li><a href="perfil_admin.html">Mi Perfil</a></li>
                <li><a href="materias_admin.html">Materias</a></li>
                <li><a href="temas_admin.html">Temas</a></li>
                <li><a href="preguntas_admin.html">Preguntas</a></li>
                <li><a href="admin_usuarios.php">Gestionar usuarios</a></li>
                <hr class="sidebar-divider">
                <li><a href="login.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="content-card">
            <h2>Lista de Materias</h2>
            <ul class="materias-list">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');
                        echo "<li><a href='#'>{$nombre}</a></li>";
                    }
                } else {
                    echo "<li class='no-materias'>No hay materias registradas.</li>";
                }

                $conn->close();
                ?>
            </ul>
            <div class="add-button-container">
                <a href="add_materia.html" class="add-button">+</a>
            </div>
        </div>

    <a href="dashboard_admin.html" class="back-button">Volver atrás</a>
    </main>
    <script src="materias_admin.js" defer></script>
</body>
</html>
