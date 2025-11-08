<?php
require_once'lib/common.php';
session_start();


$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'");


if (mysqli_num_rows($resultado) == 0) {
    
    session_destroy();
    header("Location: login.html");
    exit;
}

$usuario_data = mysqli_fetch_assoc($resultado);
$resultado = mysqli_query($conexion,"$usuario_data");

// Obtener estadísticas del usuario
$stmt = mysqli_prepare($conexion, "SELECT COUNT(*) as total_blogs, AVG(palabra_count) as avg_words FROM post WHERE usuario_id = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
mysqli_stmt_execute($stmt);
$stats_result = mysqli_stmt_get_result($stmt);
$stats = mysqli_fetch_assoc($stats_result);
mysqli_stmt_close($stmt);

// Calcular días desde registro
$fecha_registro = new DateTime($usuario_data['fecha_registro']);
$hoy = new DateTime();
$dias_registrado = $hoy->diff($fecha_registro)->days;

// Calcular nivel de escritor
// $grade = min(6, max(1, floor($stats['total_blogs'] / 2) + 1));

// mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - CbNoticias</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="account-inf.css">
</head>

<body>
    <nav class="nav">
        <div>
            <h2>📰 CbNoticias</h2>
        </div>
        <div class="nav-links">
            <a href="LP.php">Inicio</a>
            <a href="Read.php">Leer Blogs</a>
            <a href="Write.html">Escribir</a>
            <a href="Account-info.php">Mi Cuenta</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="account-container">
        <div class="account-header">
            <h1>👤 Mi Cuenta</h1>
            <p>Gestiona tu información personal y estadísticas</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $stats['total_blogs']; ?></div>
                <div class="stat-label">Blogs Publicados</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $dias_registrado; ?></div>
                <div class="stat-label">Días Registrado</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo round($stats['avg_words'] ?: 0); ?></div>
                <div class="stat-label">Promedio Palabras</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $grade; ?></div>
                <div class="stat-label">Nivel Escritor</div>
            </div>
        </div>

        <div class="account-grid">
            <div class="info-card">
                <h3>📝 Información Personal</h3>
                <div class="info-item">
                    <span class="info-label">Usuario:</span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario_data['usuario']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario_data['nombre']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Correo:</span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario_data['correo']); ?></span>
                </div>
            </div>

            <div class="info-card">
                <h3>🎭 Preferencias</h3>
                <div class="info-item">
                    <span class="info-label">Género Favorito:</span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario_data['genero_lit_fav'] ?: 'No especificado'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value"><?php echo htmlspecialchars($usuario_data['telefono'] ?: 'No especificado'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tipo de Usuario:</span>
                    <span class="info-value">
                        <span class="grade-indicator grade-<?php echo $grade; ?>">
                            <?php echo ucfirst($usuario_data['tipo_usuario']); ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <div class="edit-section">
            <h3>✏️ Editar Información</h3>
            <p style="margin-bottom: 1rem;">Actualiza tus datos personales y preferencias</p>
            <button class="btn" onclick="window.location.href='updateAcc.php'" id="editBtn">Editar Información</button>
        </div>
    </div>
</body>
</html>
