<?php
require_once 'lib/common.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'post')
{
    $pdo = getPDO();
    
    // Get credentials
    $usuario = trim($_POST['user']);
    $clave = $_POST['clave'];
    
    try {
        // Try to login
        $userData = tryLogin($pdo, $usuario, $clave);
        
        if ($userData)
        {
            // Login successful
            login($userData['user'], $userData['nombre'], $userData['genero_lit_fav']);

            header('Location: LP.php');
            exit();
        }
        else
        {
            $error = 'Usuario o contraseña incorrectos';
        }
    } catch (Exception $e) {
        $error = 'Error en el sistema: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Iniciar Sesión - CbNoticias</title>
</head>
<body>
  <div class="container">
    <form action="login.php" method="POST" class="form" id="loginForm">
      <h2>Iniciar Sesión</h2>
      
      <?php if ($error): ?>
        <div style="background: var(--error); color: white; padding: 10px; border-radius: 8px; margin-bottom: 1rem;">
          <?php echo htmlEscape($error); ?>
        </div>
      <?php endif; ?>
      
      <input type="text" name="usuario" placeholder="Usuario" required 
             value="<?php echo isset($usuario) ? htmlEscape($usuario) : ''; ?>">
      <input type="password" name="clave" placeholder="Contraseña" required>
      <button type="submit" id="submitBtn">Entrar</button>
      <p>¿No tienes cuenta? <a href="registrar.php">Regístrate</a></p>
      <p><a href="install.php">Instalar base de datos</a></p>
    </form>
  </div>
</body>
</html>