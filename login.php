<?php
require_once 'lib/common.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $pdo = getPDO();
    
    // Get credentials
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];
    
    try {
        // Query to find user
        $stmt = $pdo->prepare('SELECT usuario, nombre FROM user WHERE usuario = :usuario AND clave = :clave');
        $stmt->execute([
            'usuario' => $usuario,
            'clave' => $clave
        ]);
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData)
        {
            // Login successful - set session variables
            $_SESSION['usuario'] = $userData['usuario'];
            $_SESSION['nombre'] = $userData['nombre'];
            $_SESSION['logged_in'] = true;
            
            header('Location: LP.php');
            exit();
        }
        else
        {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
    catch (Exception $e)
    {
        $error = 'Error al procesar el login: ' . $e->getMessage();
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
    <form action="login.php" method="POST" class="form">
      <h2>Iniciar Sesión</h2>
      
      <?php if ($error): ?>
        <div style="background: var(--error); color: white; padding: 10px; border-radius: 8px; margin-bottom: 1rem;">
          <?php echo htmlEscape($error); ?>
        </div>
      <?php endif; ?>
      
      <input type="text" name="usuario" placeholder="Usuario" required autocomplete="username">
      <input type="password" name="clave" placeholder="Contraseña" required autocomplete="current-password">
      <button type="submit">Entrar</button>
      <p>¿No tienes cuenta? <a href="register.html">Regístrate</a></p>
    </form>
  </div>
</body>
</html>