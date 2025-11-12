<?php
require_once'lib/common.php';
session_start();
requiereLogin();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CbNoticias - Panel Principal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Lp.css">
</head>
<body>
    <nav class="nav">
        <div>
            <h2>📰 CbNoticias</h2>
        </div>
        <div class="nav-links">
            <a href="LP.php">Inicio</a>
            <a href="Read.php">Leer Blogs</a>
            <a href="Write.php">Escribir</a>
            <a href="Account-info.php">Mi Cuenta</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="landing-container">
        <div class="welcome-section">
            <div class="user-info">
                <h3>¡Bienvenido, 
                    <?php echo $_SESSION['nombre'] ;
                     ?>!
                </h3>
                <p>Género favorito: <?php echo $_SESSION['genero_lit_fav']; ?></p>
            </div>
            <h1>Tu Plataforma de Blogs</h1>
            <p>Comparte tus ideas, lee historias increíbles y conecta con otros escritores</p>
        </div>

        <div class="sections-grid">
            <div class="section-card">
                <div class="section-icon">✍️</div>
                <h3 class="section-title">Escribir Blog</h3>
                <p class="section-description">
                    Crea y publica tus propios artículos. Comparte tus ideas, experiencias y conocimientos con la comunidad.
                </p>
                <a href="Write.php" class="section-btn btn">Comenzar a Escribir</a>
            </div>

            <div class="section-card">
                <div class="section-icon">📖</div>
                <h3 class="section-title">Leer Blogs</h3>
                <p class="section-description">
                    Descubre artículos fascinantes de otros usuarios. Explora diferentes temas y géneros literarios.
                </p>
                <a href="Read.php" class="section-btn btn">Explorar Blogs</a>
            </div>
              <div class="section-card">
                <div class="section-icon"><h1>!</h1></div>
                <h3 class="section-title">Descubre lo ilimitable</h3>
                <p class="section-description">
                   Otros foros// bajo construcción
                </p>
                <a href="Write.php" class="" aria-disabled="true">...</a>
            </div>

        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.section-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>
