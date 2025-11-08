<?php
session_start();
include 'conexion.php';

// Fetch los datos del formulario
$nombre = trim($_POST["nombre"]);
$correo = trim($_POST["correo"]);
$usuario = trim($_POST["usuario"]);
$clave = trim($_POST["clave"]);
$telefono = trim($_POST["telefono"]);
$genero_lit_fav = $_POST["genero_lit_fav"];


// Verificar si el usuario ya existe
// $verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'");
// if (mysqli_num_rows($verificar_usuario) > 0) {
//     echo '<script>
//     alert("El usuario ya existe");
//     window.history.go(-1);
//     </script>';m
// }

// Insertar los datos usando prepared statement
$insertar = "INSERT INTO usuarios (nombre, correo, usuario, clave, telefono, genero_lit_fav) VALUES ('$nombre', '$correo', '$usuario', '$clave', '$telefono', '$genero_lit_fav')";

$resultado = mysqli_query($conexion, $insertar);


if (!$resultado) {
    echo '<script>
    alert("Error al registrarse");
    </script>';
} else {
    // Guardar datos en sesión para auto-login
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['genero_lit_fav'] = $genero_lit_fav;
    
    echo '<script>
    alert("Tu cuenta se registró exitosamente");
    window.location = "LP.php";
    </script>';
}

mysqli_close($conexion);
?>