<?php

function getRootPath(){
    return realpath(__DIR__ . '/..');
}

function getDatabasePath(){
    return getRootPath() . '/data/data.sqlite';
}

function getDsn(){
    return 'sqlite:' . getDatabasePath();
}

function getPDO()
{
    $pdo = new PDO(getDsn());

    // Foreign key constraints need to be enabled manually in SQLite
    $result = $pdo->query('PRAGMA foreign_keys = ON');
    if ($result === false)
    {
        throw new Exception('Could not turn on foreign key constraints');
    }

    return $pdo;
}


function htmlEscape($html)
{
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

function convertSqlDate($sqlDate)
{
    if (empty($sqlDate)) {
        return 'Unknown date';
    }
    
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);
    
    if ($date === false) {
        $date = DateTime::createFromFormat('Y-m-d', $sqlDate);
    }
    
    if ($date === false) {
        return $sqlDate;
    }
    
    return $date->format('d/m/Y');
}

/**
 * Try to login a user
 * @return array|false Returns user data or false on failure
 */
function tryLogin(PDO $pdo, $usuario, $clave)
{
    $sql = "
        SELECT
            id_usr, usuario, nombre, email, clave, genero_lit
        FROM
            user
        WHERE
            usuario = :usuario
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Direct password comparison (plain text - no hashing)
    if ($user && $user['clave'] === $clave) {
        return $user;
    }
    
    return false;
}

/**
 * Log in a user by setting session variables
 */
function login($usuario, $nombre, $genero_lit = null)
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['genero_lit_fav'] = $genero_lit;
    $_SESSION['logged_in'] = true;
}
/**
 * Check if user is logged in
 */

function isLoggedIn()
{
    return isset($_SESSION['logged_in_username']);
}
/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function fetchgenerolitfav(PDO $pdo, $usuario)
{
    $stmt = $pdo->prepare("SELECT genero_lit FROM user WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    return $stmt->fetchColumn();
}

function userExists(PDO $pdo, $usuario)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    return $stmt->fetchColumn() > 0;
}

function emailExists(PDO $pdo, $correo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE correo = :correo");
    $stmt->execute([':correo' => $correo]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Log out current user
 */
function logout()
{
    session_unset();
    session_destroy();
}

/**
 * Get current logged in user's username
 */
function getCurrentUser()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

function fetchAllusuarios() {
    $pdo = getPDO();

    // Prepare and execute the query
    $stmt = $pdo->prepare('
        SELECT usuario, nombre, email, genero_lit
        FROM user
        ORDER BY usuario ASC
    ');

    if (!$stmt->execute()) {
        throw new Exception('Failed to fetch users from database');
    }

    // Return same kind of data that mysqli_stmt_get_result() provided
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Fetch all posts from database
 */
function fetchAllPosts() {
    $pdo = getPDO();
    $query = $pdo->query('
        SELECT title, subtitle, author_name, content, created_at, tag
        FROM post
        ORDER BY created_at DESC
    ');

    if ($query === false) {
        throw new Exception('Failed to fetch posts from database');
    }

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch all comments from database
 */
function fetchAllComments() {  
    $pdo = getPDO();
    $query = $pdo->query('
        SELECT user_id_C, text, created_at, grade
        FROM comment
        ORDER BY created_at DESC
    ');

    if ($query === false) {
        throw new Exception('Failed to fetch comments from database');
    }

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

?>