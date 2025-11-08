<?php
require_once 'lib/common.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getPDO();
    
    // Get and sanitize input
    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $usuario = trim($_POST["usuario"] ?? '');
    $clave = $_POST["clave"] ?? '';
    $genero_lit = $_POST["genero_lit"] ?? '';
    $fecha_registro = date('Y-m-d H:i:s');
    //Es interesante utilizar placeholders en las consultas preparadas para evitar inyecciones SQL.
    $error = '';
    $success = false;
    
    try {
        // Basic validation before DB queries duh
        if (empty($nombre) || empty($correo) || empty($usuario) || empty($clave)) {
            $error = "Todos los campos son obligatorios.";
        }
        // Check if username exists
        elseif (userExists($pdo, $usuario)) {
            $error = "El usuario ya existe.";
        }
        // Check if email exists
        elseif (emailExists($pdo, $correo)) {
            $error = "El correo ya está registrado.";
        }
        else {
            // blegh
            $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, correo, usuario, clave, fecha_registro, grade, genero_lit) 
                VALUES (:nombre, :correo, :usuario, :clave, :fecha_registro, 1, :genero_lit)
            ");
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':usuario' => $usuario,
                ':clave' => $hashedPassword,
                ':fecha_registro' => $fecha_registro,
                ':genero_lit' => $genero_lit
            ]);
            
            $success = true;
        }
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        $error = "Error al registrarse. Intenta nuevamente.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title>Registro - CbNoticias</title>
</head>
<body>
  <div class="container">
    <form action="registrar.php" method="post" class="form" id="registerForm">
      <h2>Crear Cuenta</h2>
      
      <div class="input-wrapper">
        <input type="text" name="nombre" id="nombre" placeholder="Nombre (letras y espacios)" required>
        <div class="validation-box" id="nombreMsg"></div>
      </div>
      
      <div class="input-wrapper">
        <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
        <div class="validation-box" id="correoMsg"></div>
      </div>
      
      <div class="input-wrapper">
        <input type="text" name="usuario" id="usuario" placeholder="Usuario (3-20 caracteres)" required>
        <div class="validation-box" id="usuarioMsg"></div>
      </div>
      
      <div class="input-wrapper">
        <input type="password" name="clave" id="clave" placeholder="Contraseña (mín. 6 caracteres)" required>
        <div class="validation-box" id="claveMsg"></div>
      </div>
      
      <div class="input-wrapper">
        <input type="tel" name="telefono" id="telefono" placeholder="Teléfono (+52)" required>
        <div class="validation-box" id="telefonoMsg"></div>
      </div>
      
      <div>
        <select name="genero_lit_fav" id="genero_lit">
          <option value="">Selecciona tu género literario favorito</option>
          <option value="Ficción">Ficción</option>
          <option value="No Ficción">No Ficción</option>
          <option value="Ciencia Ficción">Ciencia Ficción</option>
          <option value="Romance">Romance</option>
          <option value="Misterio">Misterio</option>
          <option value="Fantasía">Fantasía</option>
          <option value="Historia">Historia</option>
          <option value="Biografía">Biografía</option>
          <option value="Poesía">Poesía</option>
          <option value="General">General</option>
        </select>
      </div>
      
      <button type="submit" id="submitBtn" disabled>Registrarse</button>
      <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </form>
  </div>

  <script src="script.js"></script>
</body>
</html>