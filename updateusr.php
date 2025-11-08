<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}
include"conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Account-info.php");
    exit;
}

include('conexion.php');

// Obtener datos del formulario y sanitizar
$nombre = trim($_POST['nombre']);
$correo = trim($_POST['correo']);
$telefono = !empty($_POST['telefono']) ? trim($_POST['telefono']) : null;
$genero_lit_fav = !empty($_POST['genero_lit_fav']) ? trim($_POST['genero_lit_fav']) : null;
$nueva_clave = !empty($_POST['nueva_clave']) ? trim($_POST['nueva_clave']) : null;
$confirmar_clave = !empty($_POST['confirmar_clave']) ? trim($_POST['confirmar_clave']) : null;
$user_id = $_SESSION['id'];

// Validaciones del lado del servidor
$errores = [];

// Validar nombre (solo mayúsculas y espacios)
if (!preg_match('/^[A-ZÁÉÍÓÚÑ\s]+$/', $nombre)) {
    $errores[] = "El nombre solo debe contener letras mayúsculas y espacios";
}

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo electrónico no es válido";
}

// Validar teléfono si se proporcionó
if ($telefono !== null && !preg_match('/^[0-9]{10}$/', $telefono)) {
    $errores[] = "El teléfono debe tener exactamente 10 dígitos";
}

// Validar contraseña si se proporcionó
if ($nueva_clave !== null) {
    if ($nueva_clave !== $confirmar_clave) {
        $errores[] = "Las contraseñas no coinciden";
    }
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $nueva_clave)) {
        $errores[] = "La contraseña debe tener mínimo 6 caracteres con letras y números";
    }
}

// Si hay errores, mostrarlos y detener
if (!empty($errores)) {
    echo '<script>
        alert("Errores de validación:\\n' . implode('\\n', $errores) . '");
        window.history.go(-1);
    </script>';
    exit;
}

// Verificar que el correo no esté siendo usado por otro usuario
$stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE correo = ? AND id != ?");
mysqli_stmt_bind_param($stmt, "si", $correo, $user_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    echo '<script>
        alert("El correo electrónico ya está siendo usado por otro usuario");
        window.history.go(-1);
    </script>';
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    exit;
}
mysqli_stmt_close($stmt);

// Preparar la actualización
if ($nueva_clave !== null) {
    // Actualizar con nueva contraseña
    $stmt = mysqli_prepare($conexion, 
        "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, genero_lit_fav = ?, clave = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $correo, $telefono, $genero_lit_fav, $nueva_clave, $user_id);
} else {
    // Actualizar sin cambiar contraseña
    $stmt = mysqli_prepare($conexion, 
        "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, genero_lit_fav = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $correo, $telefono, $genero_lit_fav, $user_id);
}

// Ejecutar la actualización
if (mysqli_stmt_execute($stmt)) {
    // Actualizar el nombre en la sesión si cambió
    $_SESSION['nombre'] = $nombre;
    
    echo '<script>
        alert("✅ Tu información se actualizó exitosamente");
        window.location = "Account-info.php";
    </script>';
} else {
    echo '<script>
        alert("❌ Error al actualizar los datos. Por favor intenta de nuevo.");
        window.history.go(-1);
    </script>';
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
