<?php
session_start();
include 'conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

// Recibir datos del formulario
$titulo = trim($_POST["titulo"]);
$subtitulo = trim($_POST["subtitulo"]);
$contenido = trim($_POST["contenido"]);
$tag = $_POST["tag"];
$usuario_id = $_SESSION['id'];

// Validaciones básicas
if (empty($titulo) || empty($contenido)) {
    echo '<script>
    alert("El título y contenido son obligatorios");
    window.history.go(-1);
    </script>';
    exit;
}

if (strlen($titulo) < 5) {
    echo '<script>
    alert("El título debe tener al menos 5 caracteres");
    window.history.go(-1);
    </script>';
    exit;
}

if (strlen($contenido) < 50) {
    echo '<script>
    alert("El contenido debe tener al menos 50 caracteres");
    window.history.go(-1);
    </script>';
    exit;
}

// Calcular estadísticas
$palabras = str_word_count($contenido);
$tiempo_lectura = ceil($palabras / 200); // 200 palabras por minuto

// Insertar blog en la base de datos
$stmt = mysqli_prepare($conexion, "INSERT INTO blogs (usuario_id, titulo, subtitulo, contenido, palabra_count, tiempo_lectura, tag) VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "isssiis", $usuario_id, $titulo, $subtitulo, $contenido, $palabras, $tiempo_lectura, $tag);

if (mysqli_stmt_execute($stmt)) {
    echo '<script>
    alert("¡Blog publicado exitosamente!");
    window.location = "Read.html";
    </script>';
} else {
    echo '<script>
    alert("Error al publicar el blog: ' . mysqli_error($conexion) . '");
    window.history.go(-1);
    </script>';
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
