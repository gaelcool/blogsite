<?php
require_once 'lib/common.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'get')
{
    $pdo = getPDO();
    
    // Get credentials
    $usuario = trim($_GET['user']);
    $clave = $_POST['clave'];
    
    try {
        // Try to login
        $userData = intentaLogin($pdo, $usuario, $clave);
        
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
    <title>Sobre Nosotros - CbNoticias</title>
     
      <link rel="stylesheet" href="us.css">
</head>
<body>
    <div class="aestheticsubheadinvis">
   
 </div>
    <div class="wrapper">
        <header>
  <div class="logo">
    <h1>CbNoticias</h1>
  </div>

  <nav>
    <a href="index.html">Inicio</a>
    <a href="us.html" class="active">Sobre Nosotros</a>
    <a href="registrar.php">Registro</a>
  </nav>
 
  <form action="login.php" method="POST" class="login-form">
    <input type="text" name="user" placeholder="Usuario">
    <input type="password" name="clave" placeholder="Contraseña">
    <button type="submit" id="submitBtn">Entrar a tu plaza</button>
  </form>
</header>

<div class="bottom_right"></div>
        <main class="container">
            <section class="intro">
                <h2>Sobre Nosotros</h2>
                <p>Cbnoticias busca fomentar comunicacion estudiantil.</p> 
                 <div class='rightbot'><strong>Abierto las 24 horas del dia, los 365 dias del año.</strong>
 </div>       

    </section>

            <secton class="localANDtrian">   
             <div class="location">
                <h3>Nuestra Ubicación:</h3>
                <div class="map-container">
                <img src="CroquisCbtisMainbuilding.jpg" alt="mapa">
                </div>
                <p class="address">
                    <strong>Dirección:</strong> Av. Principal 123, Tlaxcala, Mexico<br>
                   
                </p>
             </div>
            
            </secton>



            <section class="certifications">
                <h3>Iniciativas:</h3>
                <div class="cert-grid">
                    <div class="cert-card">
                        <img src="cert1.gif" alt="Promover comunicacion">
                        <h4>Politica social</h4>
                        <p>Gestión Ambiental Certificada</p>
                    </div>
                    <div class="cert-card">
                        <img src="cert2.gif" alt="Educación emocional">
                        <h4>Honestidad</h4>
                        <p>Asociación Mundial </p>
                    </div>
                    <div class="cert-card">
                        <img src="yah4.gif" alt="Chisme sin lastimar">
                        <h4>Representación</h4>
                        <p>Asociación de Acuarios :3</p>
                    </div>
                </div>
            </section>

            <section class="mission">
                <h3>Nuestra Misión</h3>
                <p>Crear un espacio para estudiantes del cbtis 03.</p>
            </section>
        </main>

        
    <footer>
        <div class="footer-content">
            <div class="footer-info">
                <h3>Cbnoticias</h3>
                <p>localhost:unpuerto/cbnoticias/</p>
            </div>
            <div class="footer-contact">
                <div class="bottom_left">
                <p>2025 CbNoticias&copy; Suerte.</p>
                </div>
            </div>
        </div>
    </footer>
    </div>
</body>
</html>