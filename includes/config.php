<?php
// base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_votaciones');


// Conexion
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch(PDOException $e) {
    die("ERROR: No se pudo conectar a la base de datps. " . $e->getMessage());
}

session_start();

//  limpiar 
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//seguridas
define('SECRET_KEY', 'tu_clave_secreta_para_hashing');
define('ENCRYPTION_KEY', 'tu_clave_de_encriptacion');
// hash 
function generate_hash($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// verificar hash
function verify_hash($password, $hash) {
    return password_verify($password, $hash);
}
?>