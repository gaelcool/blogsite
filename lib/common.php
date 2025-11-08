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

function getPDO(){
    $pdo = new PDO(getDsn());
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

//  Log in a user by setting session variables
 
// function login($usuario, $nombre)
// {
//     session_regenerate_id(true);
//     $_SESSION['usuario'] = $usuario;
//     $_SESSION['nombre'] = $nombre;
//     $_SESSION['logged_in'] = true;
// }

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}


/**
 * Get current logged in user's username
 */
function getCurrentUser()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

/**
 * Check if a user exists by username
 */
function userExists(PDO $pdo, $usuario)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Check if an email exists
 */
function emailExists(PDO $pdo, $correo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
    $stmt->execute([':correo' => $correo]);
    return $stmt->fetchColumn() > 0;
}

function fetchAllPosts() {
    $pdo = getPDO();
    $query = $pdo->query('
        SELECT title, subtitle, author_name, content, created_at
        FROM post
        ORDER BY created_at DESC
    ');

    if ($query === false) {
        throw new Exception('Failed to fetch posts from database');
    }

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function fetchAllComments() {  
    $pdo = getPDO();
    $query = $pdo->query('
        SELECT user_id_C, text, created_at
        FROM comment
        ORDER BY created_at DESC
    ');

    if ($query === false) {
        throw new Exception('Failed to fetch comments from database');
    }

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

?>