<?php
require_once 'config.php';

// Función para redirigir según el rol
function redirect_based_on_role() {
    if (!isset($_SESSION['id_rol'])) {
        header("Location: login.php");
        exit();
    }
    
    switch ($_SESSION['id_rol']) {
        case 1: // SuperAdmin
            header("Location: superadmin/dashboard.php");
            break;
        case 2: // Admin
            header("Location: admin/dashboard.php");
            break;
        case 3: // Usuario
            header("Location: usuario/dashboard.php");
            break;
        default:
            header("Location: login.php");
    }
    exit();
}

// Función para generar un token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Función para obtener el nombre del rol
function get_role_name($role_id) {
    global $pdo;
    
    try {
        $sql = "SELECT nombre FROM roles WHERE id_rol = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $role_id]);
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        return "Desconocido";
    }
}

// Función para obtener la ciudad de un usuario
function get_user_city($user_id) {
    global $pdo;
    
    try {
        $sql = "SELECT c.nombre 
                FROM usuarios u 
                JOIN ciudades c ON u.id_ciudad = c.id_ciudad 
                WHERE u.id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        return "Desconocida";
    }
}
?>