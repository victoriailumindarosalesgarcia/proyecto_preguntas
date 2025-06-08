<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administrar Preguntas</title>
    <link rel="stylesheet" href="preguntas_admin.css" />
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
            <span class="navbar-title">Administrar Preguntas</span>
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
            <li><a href="preguntas_admin.php">Preguntas</a></li>
            <hr class="sidebar-divider">
            <li><a href="login.html">Cerrar Sesión</a></li>
        </ul>
    </nav>
</aside>

<main class="main-content">
    <div class="content-card">
        <div class="header-actions">
            <h2>Todas las Preguntas</h2>
            <a href="question_type_selection.html?from=preguntas_admin.php" class="button add-button">+ Nueva Pregunta</a>
        </div>

        <div class="questions-list">
            <?php
            $conexion = mysqli_connect("localhost", "root", "", "pag");

            if (!$conexion) {
                echo "<p>Error al conectar con la base de datos: " . mysqli_connect_error() . "</p>";
            } else {
                // Preguntas Abiertas
                $sql_abiertas = "SELECT * FROM pregunta_abierta ORDER BY id_pregunta DESC LIMIT 5";
                $result_abiertas = mysqli_query($conexion, $sql_abiertas);

                if (mysqli_num_rows($result_abiertas) > 0) {
                    echo "<h3>Preguntas Abiertas</h3>";
                    while ($preg = mysqli_fetch_assoc($result_abiertas)) {
                        echo "<div style='border:1px solid #6699cc; margin-bottom:10px; padding:10px; background-color:#f2f8ff;'>";
                        echo "<p><strong>Pregunta Abierta:</strong> " . htmlspecialchars($preg['texto']) . "</p>";

                        if (!empty($preg['imagen'])) {
                            $imagenBase64 = base64_encode($preg['imagen']);
                            echo "<img src='data:image/jpeg;base64,{$imagenBase64}' alt='Imagen pregunta' style='max-width:200px;' /><br>";
                        }

                        echo "<p><strong>Dificultad:</strong> " . htmlspecialchars($preg['dificultad']) . "</p>";
                        echo "<p><strong>Estado:</strong> " . htmlspecialchars($preg['estado'] ?? 'N/A') . "</p>";
                        echo "<p><a href='#'>Editar</a> | <a href='#'>Eliminar</a></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay preguntas abiertas registradas.</p>";
                }

                // Preguntas Verdadero/Falso
                $sql_vf = "SELECT * FROM pregunta_vf ORDER BY id_pregunta DESC LIMIT 5";
                $result_vf = mysqli_query($conexion, $sql_vf);

                if (mysqli_num_rows($result_vf) > 0) {
                    echo "<h3>Preguntas de Verdadero/Falso</h3>";
                    while ($preg = mysqli_fetch_assoc($result_vf)) {
                        echo "<div style='border:1px solid #ffcc66; margin-bottom:10px; padding:10px; background-color:#fffaf0;'>";
                        echo "<p><strong>Pregunta V/F:</strong> " . htmlspecialchars($preg['texto']) . "</p>";

                        if (!empty($preg['imagen'])) {
                            $imagenBase64 = base64_encode($preg['imagen']);
                            echo "<img src='data:image/jpeg;base64,{$imagenBase64}' alt='Imagen pregunta' style='max-width:200px;' /><br>";
                        }

                        echo "<p><strong>Dificultad:</strong> " . htmlspecialchars($preg['dificultad']) . "</p>";
                        echo "<p><strong>Respuesta Correcta:</strong> " . htmlspecialchars($preg['respuesta']) . "</p>";
                        echo "<p><strong>Estado:</strong> " . htmlspecialchars($preg['estado'] ?? 'N/A') . "</p>";
                        echo "<p><a href='#'>Editar</a> | <a href='#'>Eliminar</a></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay preguntas de Verdadero/Falso registradas.</p>";
                }

                mysqli_close($conexion);
            }
            ?>
        </div>
    </div>
</main>

<script src="preguntas_admin.js"></script>
</body>
</html>