<?php
require_once 'lib/common.php';
session_start();

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get user's favorite genre if available
$genero_fav = $_SESSION['genero_lit_fav'] ?? 'General';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escribir Blog - CbNoticias</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .write-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .write-form {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px var(--shadow);
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
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--secondary);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }
        
        .form-group textarea {
            min-height: 300px;
            resize: vertical;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--background);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--accent);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text);
            opacity: 0.8;
        }
        
        .char-counter {
            font-size: 0.8rem;
            color: var(--text);
            opacity: 0.7;
            text-align: right;
            margin-top: 0.5rem;
        }
        
        .submit-section {
            text-align: center;
            margin-top: 2rem;
        }
        
        .submit-btn {
            padding: 15px 30px;
            font-size: 1.1rem;
            min-width: 200px;
        }
        
        .back-btn {
            background: var(--secondary);
            margin-right: 1rem;
        }
        
        .back-btn:hover {
            background: #FFA0B4;
        }
    </style>
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

    <div class="write-container">
        <div class="write-form">
            <h1>✍️ Escribir Nuevo Blog</h1>
            
            <form action="save-blog.php" method="POST" id="blogForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título del Blog</label>
                        <input type="text" name="titulo" id="titulo" placeholder="Escribe un título atractivo" maxlength="200" required>
                        <div class="char-counter" id="tituloCounter">0/200</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tag">Género/Tag</label>
                        <select name="tag" id="tag">
                            <option value="<?php echo htmlEscape($genero_fav); ?>"><?php echo htmlEscape($genero_fav); ?></option>
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
                </div>
                
                <div class="form-group">
                    <label for="subtitulo">Subtítulo (opcional)</label>
                    <input type="text" name="subtitulo" id="subtitulo" placeholder="Un subtítulo descriptivo" maxlength="300">
                    <div class="char-counter" id="subtituloCounter">0/300</div>
                </div>
                
                <div class="form-group">
                    <label for="contenido">Contenido del Blog</label>
                    <textarea name="contenido" id="contenido" placeholder="Escribe tu blog aquí..." required></textarea>
                </div>
                
                <div class="stats-bar">
                    <div class="stat-item">
                        <div class="stat-value" id="wordCount">0</div>
                        <div class="stat-label">Palabras</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="readingTime">0</div>
                        <div class="stat-label">Min. Lectura</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="charCount">0</div>
                        <div class="stat-label">Caracteres</div>
                    </div>
                </div>
                
                <div class="submit-section">
                    <a href="LP.php" class="btn back-btn">← Volver</a>
                    <button type="submit" class="btn submit-btn">📝 Publicar Blog</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character counters
        function updateCounter(input, counter, max) {
            const length = input.value.length;
            counter.textContent = `${length}/${max}`;
            
            if (length > max * 0.9) {
                counter.style.color = 'var(--error)';
            } else {
                counter.style.color = 'var(--text)';
            }
        }
        
        // Word count and reading time calculation
        function updateStats() {
            const content = document.getElementById('contenido').value;
            const words = content.trim().split(/\s+/).filter(word => word.length > 0);
            const wordCount = words.length;
            const charCount = content.length;
            const readingTime = Math.ceil(wordCount / 200); // 200 words per minute
            
            document.getElementById('wordCount').textContent = wordCount;
            document.getElementById('readingTime').textContent = readingTime;
            document.getElementById('charCount').textContent = charCount;
        }
        
        // Event listeners
        document.getElementById('titulo').addEventListener('input', function() {
            updateCounter(this, document.getElementById('tituloCounter'), 200);
        });
        
        document.getElementById('subtitulo').addEventListener('input', function() {
            updateCounter(this, document.getElementById('subtituloCounter'), 300);
        });
        
        document.getElementById('contenido').addEventListener('input', updateStats);
        
        // Form validation
        document.getElementById('blogForm').addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const contenido = document.getElementById('contenido').value.trim();
            
            if (titulo.length < 5) {
                e.preventDefault();
                alert('El título debe tener al menos 5 caracteres');
                return;
            }
            
            if (contenido.length < 50) {
                e.preventDefault();
                alert('El contenido debe tener al menos 50 caracteres');
                return;
            }
        });
        
        // Auto-save draft (sessionStorage instead of localStorage)
        function saveDraft() {
            const draft = {
                titulo: document.getElementById('titulo').value,
                subtitulo: document.getElementById('subtitulo').value,
                contenido: document.getElementById('contenido').value,
                tag: document.getElementById('tag').value
            };
            sessionStorage.setItem('blogDraft', JSON.stringify(draft));
        }
        
        function loadDraft() {
            const draft = sessionStorage.getItem('blogDraft');
            if (draft) {
                const data = JSON.parse(draft);
                document.getElementById('titulo').value = data.titulo || '';
                document.getElementById('subtitulo').value = data.subtitulo || '';
                document.getElementById('contenido').value = data.contenido || '';
                document.getElementById('tag').value = data.tag || '<?php echo htmlEscape($genero_fav); ?>';
                
                updateCounter(document.getElementById('titulo'), document.getElementById('tituloCounter'), 200);
                updateCounter(document.getElementById('subtitulo'), document.getElementById('subtituloCounter'), 300);
                updateStats();
            }
        }
        
        // Load draft on page load
        document.addEventListener('DOMContentLoaded', loadDraft);
        
        // Save draft every 30 seconds
        setInterval(saveDraft, 30000);
        
        // Clear draft on successful submit
        document.getElementById('blogForm').addEventListener('submit', function() {
            sessionStorage.removeItem('blogDraft');
        });
    </script>
</body>
</html>