<?php
require_once 'config.php';

// Verificar si el usuario está logueado
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Verificar rol del usuario
function check_role($required_role) {
    if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != $required_role) {  // Fix: $_SESSION['id_rol']
        header("Location: login.php");
        exit();
    }
    
    $user_role = $_SESSION['id_rol'];
    
    // SuperAdmin puede acceder a todo
    if ($user_role == 1) return true;
    
    // Admin puede acceder a funciones de admin y usuario
    if ($user_role == 2 && ($required_role == 2 || $required_role == 3)) return true;
    
    // Usuario solo puede acceder a funciones de usuario
    if ($user_role == 3 && $required_role == 3) return true;
    
    // Si no cumple con los requisitos
    header("Location: login.php");
    exit();
}

// Registrar actividad en el log
function log_activity($action) {
    global $pdo;
    
    // Genera un ID único para el log (ejemplo con uniqid() o timestamp)
    $id_log = uniqid();  // Opción 1: ID corto basado en tiempo (ej: "65f3a7b1e4a02")
    // $id_log = time() . bin2hex(random_bytes(4));  // Opción 2: ID más largo y único

    $sql = "INSERT INTO log_auditoria (id_log, usuario, accion, ip_origen) 
            VALUES (:id_log, :usuario, :accion, :ip)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':id_log' => $id_log,  // ¡Este es el campo PRIMARY KEY requerido!
        ':usuario' => $_SESSION['user_id'],
        ':accion' => $action,
        ':ip' => $_SERVER['REMOTE_ADDR']
    ]);
}
?>