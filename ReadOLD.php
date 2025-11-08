<?php
require_once'lib/common.php';
session_start();
requireLogin();
$blogs = fetchAllPosts();
fetchAllComments();
$resultado = fetchAllusuarios();

$error = '';


// if (!isset($_SESSION['usuario'])) {
//     header("Location: login.html");
//     exit;
// }



// Obtener todos los blogs con información del usuario
// $stmt = mysqli_prepare($conexion, "SELECT b.*, u.usuario as autor FROM blogs b JOIN usuarios u ON b.usuario_id = u.id ORDER BY b.fecha_creacion DESC");
// mysqli_stmt_execute($stmt);
// $resultado = mysqli_stmt_get_result($stmt);
// $blogs = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// mysqli_stmt_close($stmt);
// mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer Blogs - CbNoticias</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .read-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            color: var(--accent);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .page-header p {
            color: var(--text);
            font-size: 1.2rem;
        }
        
        .blog-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px var(--shadow);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .blog-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--shadow);
        }
        
        .blog-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .blog-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .blog-subtitle {
            font-size: 1rem;
            color: var(--text);
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .blog-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .blog-author {
            color: var(--accent);
            font-weight: 500;
        }
        
        .blog-date {
            color: var(--text);
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        .blog-tag {
            background: var(--primary);
            color: var(--white);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        
        .blog-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-badge {
            background: var(--background);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--text);
        }
        
        .blog-preview {
            color: var(--text);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .blog-content {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--secondary);
        }
        
        .blog-content.show {
            display: block;
        }
        
        .expand-btn {
            background: var(--accent);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .expand-btn:hover {
            background: #E55A9B;
        }
        
        .no-blogs {
            text-align: center;
            padding: 3rem;
            color: var(--text);
            opacity: 0.7;
        }
        
        .no-blogs h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .filter-section {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px var(--shadow);
        }
        
        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-select {
            padding: 8px 12px;
            border: 2px solid var(--secondary);
            border-radius: 6px;
            background: var(--white);
        }
        
        .search-input {
            flex: 1;
            min-width: 200px;
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
            <a href="Account-info.html">Mi Cuenta</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="read-container">
        <div class="page-header">
            <h1>📖 Explorar Blogs</h1>
            <p>Descubre artículos fascinantes de nuestra comunidad</p>
        </div>

        <div class="filter-section">
            <div class="filter-row">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar en títulos y contenido...">
                <select id="tagFilter" class="filter-select">
                    <option value="">Todos los géneros</option>
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
                <select id="sortFilter" class="filter-select">
                    <option value="newest">Más recientes</option>
                    <option value="oldest">Más antiguos</option>
                    <option value="longest">Más largos</option>
                    <option value="shortest">Más cortos</option>
                </select>
            </div>
        </div>

        <div id="blogsContainer">
            <?php if (empty($blogs)): ?>
                <div class="no-blogs">
                    <h3>📝 No hay blogs aún</h3>
                    <p>¡Sé el primero en escribir un blog!</p>
                    <a href="Write.html" class="btn" style="margin-top: 1rem;">Escribir Blog</a>
                </div>
            <?php else: ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card" data-tag="<?php echo htmlspecialchars($blog['tag']); ?>" data-author="<?php echo htmlspecialchars($blog['autor']); ?>">
                        <div class="blog-header">
                            <div>
                                <h3 class="blog-title"><?php echo htmlspecialchars($blog['titulo']); ?></h3>
                                <?php if (!empty($blog['subtitulo'])): ?>
                                    <p class="blog-subtitle"><?php echo htmlspecialchars($blog['subtitulo']); ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="blog-tag"><?php echo htmlspecialchars($blog['tag']); ?></span>
                        </div>
                        
                        <div class="blog-meta">
                            <span class="blog-author">👤 <?php echo htmlspecialchars($blog['autor']); ?></span>
                            <span class="blog-date">📅 <?php echo date('d/m/Y', strtotime($blog['fecha_creacion'])); ?></span>
                        </div>
                        
                        <div class="blog-stats">
                            <span class="stat-badge">📊 <?php echo $blog['palabra_count']; ?> palabras</span>
                            <span class="stat-badge">⏱️ <?php echo $blog['tiempo_lectura']; ?> min lectura</span>
                        </div>
                        
                        <div class="blog-preview">
                            <?php 
                            $preview = substr($blog['contenido'], 0, 200);
                            if (strlen($blog['contenido']) > 200) {
                                $preview .= '...';
                            }
                            echo nl2br(htmlspecialchars($preview));
                            ?>
                        </div>
                        
                        <div class="blog-content" id="content-<?php echo $blog['id']; ?>">
                            <?php echo nl2br(htmlspecialchars($blog['contenido'])); ?>
                        </div>
                        
                        <button class="expand-btn" onclick="toggleContent(<?php echo $blog['id']; ?>)">
                            <span id="btn-text-<?php echo $blog['id']; ?>">Leer más</span>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleContent(blogId) {
            const content = document.getElementById('content-' + blogId);
            const btnText = document.getElementById('btn-text-' + blogId);
            
            if (content.classList.contains('show')) {
                content.classList.remove('show');
                btnText.textContent = 'Leer más';
            } else {
                content.classList.add('show');
                btnText.textContent = 'Leer menos';
            }
        }
        
        // Filter and search functionality
        function filterBlogs() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const tagFilter = document.getElementById('tagFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;
            const blogCards = document.querySelectorAll('.blog-card');
            
            let visibleBlogs = [];
            
            blogCards.forEach(card => {
                const title = card.querySelector('.blog-title').textContent.toLowerCase();
                const content = card.querySelector('.blog-preview').textContent.toLowerCase();
                const tag = card.getAttribute('data-tag');
                const author = card.getAttribute('data-author').toLowerCase();
                
                const matchesSearch = searchTerm === '' || 
                    title.includes(searchTerm) || 
                    content.includes(searchTerm) ||
                    author.includes(searchTerm);
                
                const matchesTag = tagFilter === '' || tag === tagFilter;
                
                if (matchesSearch && matchesTag) {
                    card.style.display = 'block';
                    visibleBlogs.push(card);
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Sort blogs
            if (sortFilter === 'newest') {
                visibleBlogs.sort((a, b) => {
                    const dateA = new Date(a.querySelector('.blog-date').textContent.split('📅 ')[1].split('/').reverse().join('-'));
                    const dateB = new Date(b.querySelector('.blog-date').textContent.split('📅 ')[1].split('/').reverse().join('-'));
                    return dateB - dateA;
                });
            } else if (sortFilter === 'oldest') {
                visibleBlogs.sort((a, b) => {
                    const dateA = new Date(a.querySelector('.blog-date').textContent.split('📅 ')[1].split('/').reverse().join('-'));
                    const dateB = new Date(b.querySelector('.blog-date').textContent.split('📅 ')[1].split('/').reverse().join('-'));
                    return dateA - dateB;
                });
            } else if (sortFilter === 'longest') {
                visibleBlogs.sort((a, b) => {
                    const wordsA = parseInt(a.querySelector('.stat-badge').textContent.split('📊 ')[1].split(' ')[0]);
                    const wordsB = parseInt(b.querySelector('.stat-badge').textContent.split('📊 ')[1].split(' ')[0]);
                    return wordsB - wordsA;
                });
            } else if (sortFilter === 'shortest') {
                visibleBlogs.sort((a, b) => {
                    const wordsA = parseInt(a.querySelector('.stat-badge').textContent.split('📊 ')[1].split(' ')[0]);
                    const wordsB = parseInt(b.querySelector('.stat-badge').textContent.split('📊 ')[1].split(' ')[0]);
                    return wordsA - wordsB;
                });
            }
            
            // Reorder in DOM
            const container = document.getElementById('blogsContainer');
            visibleBlogs.forEach(card => {
                container.appendChild(card);
            });
        }
        
        // Add event listeners
        document.getElementById('searchInput').addEventListener('input', filterBlogs);
        document.getElementById('tagFilter').addEventListener('change', filterBlogs);
        document.getElementById('sortFilter').addEventListener('change', filterBlogs);
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            filterBlogs();
        });
    </script>
</body>
</html>
