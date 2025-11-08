<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
include"conexion.php";

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cuenta - CbNoticias</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="account-inf.css">
    <style>
        .update-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .update-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px var(--shadow);
        }
        
        .update-form h2 {
            text-align: center;
            color: var(--accent);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--secondary);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }
        
        .form-group small {
            display: block;
            margin-top: 0.25rem;
            color: #999;
            font-size: 0.85rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .readonly-info {
            background: #f5f5f5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <nav class="nav">
        <div>
            <h2>📰 CbNoticias</h2>
        </div>
        <div class="nav-links">
            <a href="LP.html">Inicio</a>
            <a href="Read.html">Leer Blogs</a>
            <a href="Write.html">Escribir</a>
            <a href="Account-info.php">Mi Cuenta</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="update-container">
        <form action="updateusr.php" method="post" onsubmit="updateForm">
            <h2>✏️ Actualizar Mi Información</h2>
            
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" 
                       value="<?php echo htmlspecialchars($usuario_data['usuario']); ?>" 
                       class="readonly-info" readonly>
                <small>El nombre de usuario no se puede cambiar</small>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($usuario_data['nombre']); ?>" 
                       pattern="^[A-ZÁÉÍÓÚÑ\s]+$" 
                       title="Solo mayúsculas y espacios"
                       required>
                <small>Solo letras mayúsculas y espacios</small>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico *</label>
                <input type="email" id="correo" name="correo" 
                       value="<?php echo htmlspecialchars($usuario_data['correo']); ?>" 
                       pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                       required>
                <small>Formato: ejemplo@correo.com</small>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" 
                       value="<?php echo htmlspecialchars($usuario_data['telefono'] ?: ''); ?>" 
                       pattern="^[0-9]{10}$"
                       title="10 dígitos numéricos"
                       maxlength="10">
                <small>10 dígitos (opcional)</small>
            </div>

            <div class="form-group">
                <label for="genero_lit_fav">Género Literario Favorito</label>
                <select id="genero_lit_fav" name="genero_lit_fav">
                    <option value="">Seleccionar...</option>
                    <option value="Ficción" <?php echo ($usuario_data['genero_lit_fav'] == 'Ficción') ? 'selected' : ''; ?>>Ficción</option>
                    <option value="No Ficción" <?php echo ($usuario_data['genero_lit_fav'] == 'No Ficción') ? 'selected' : ''; ?>>No Ficción</option>
                    <option value="Ciencia Ficción" <?php echo ($usuario_data['genero_lit_fav'] == 'Ciencia Ficción') ? 'selected' : ''; ?>>Ciencia Ficción</option>
                    <option value="Fantasía" <?php echo ($usuario_data['genero_lit_fav'] == 'Fantasía') ? 'selected' : ''; ?>>Fantasía</option>
                    <option value="Misterio" <?php echo ($usuario_data['genero_lit_fav'] == 'Misterio') ? 'selected' : ''; ?>>Misterio</option>
                    <option value="Romance" <?php echo ($usuario_data['genero_lit_fav'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
                    <option value="Terror" <?php echo ($usuario_data['genero_lit_fav'] == 'Terror') ? 'selected' : ''; ?>>Terror</option>
                    <option value="Biografía" <?php echo ($usuario_data['genero_lit_fav'] == 'Biografía') ? 'selected' : ''; ?>>Biografía</option>
                    <option value="Historia" <?php echo ($usuario_data['genero_lit_fav'] == 'Historia') ? 'selected' : ''; ?>>Historia</option>
                    <option value="Tecnología" <?php echo ($usuario_data['genero_lit_fav'] == 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                    <option value="Otro" <?php echo ($usuario_data['genero_lit_fav'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nueva_clave">Nueva Contraseña</label>
                <input type="password" id="nueva_clave" name="nueva_clave" 
                       pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$"
                       title="Mínimo 6 caracteres con letras y números">
                <small>Dejar en blanco para mantener la actual. Mínimo 6 caracteres con letras y números.</small>
            </div>

            <div class="form-group">
                <label for="confirmar_clave">Confirmar Nueva Contraseña</label>
                <input type="password" id="confirmar_clave" name="confirmar_clave">
                <small>Confirmar si deseas cambiar la contraseña</small>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='Account-info.php'">❌ Cancelar</button>
            </div>
        </form>
    </div>

    <script>
        // Validar que las contraseñas coincidan antes de enviar
        document.getElementById('updateForm').addEventListener('submit', function(e) {
            const nuevaClave = document.getElementById('nueva_clave').value;
            const confirmarClave = document.getElementById('confirmar_clave').value;
            
            if (nuevaClave || confirmarClave) {
                if (nuevaClave !== confirmarClave) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    return false;
                }
            }
        });

        // Validación en tiempo real del teléfono
        document.getElementById('telefono').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>