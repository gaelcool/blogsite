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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
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

function TraduceSQLfecha($sqlDate)
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


function intentaLogin(PDO $pdo, $usuario, $clave)
{
    $sql = "
        SELECT
            id_usr, usuario, nombre, email, clave, genero_lit_fav
        FROM
            user
        WHERE
            usuario = :usuario
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Direct password comparison (plain text - matching your system)
    if ($user && $user['clave'] === $clave) {
        return $user;
    }
    
    return false;
}


function login($usuario, $nombre, $genero_lit_fav = null)
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['genero_lit_fav'] = $genero_lit_fav ?? 'General';
    $_SESSION['logged_in'] = true;
}


function isLoggedIn()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Require login - redirect if not logged in
 */
function requiereLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}


function userExists(PDO $pdo, $usuario)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Check if an email exists - FIXED :)
 */
function emailExists(PDO $pdo, $email)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
    return $stmt->fetchColumn() > 0;
}


function logout()
{
    session_unset();
    session_destroy();
}

/**
 * Get current usuarios username 
 */
function getCurrentUsername()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

/**
 * Fetch TODOS los usuarios del db
 */
function fetchAllusuarios() {
    $pdo = getPDO();
    $stmt = $pdo->prepare('
        SELECT usuario, nombre, email, genero_lit_fav
        FROM user
        ORDER BY usuario ASC
    ');

    if (!$stmt->execute()) {
        throw new Exception('Failed to fetch users from database');
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


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
 * Fetch all commentarios de la db
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