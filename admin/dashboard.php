<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 2) {
    header("Location: ../login.php");
    exit();
}

$stats = [
    'usuarios_habilitados' => 0,
    'votaciones_activas' => 0,
    'votos_hoy' => 0
];

try {
    $sql = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'habilitado'";
    $stmt = $pdo->query($sql);
    $stats['usuarios_habilitados'] = $stmt->fetchColumn();
    
    $sql = "SELECT COUNT(*) as total FROM elecciones WHERE estado = 'activa' AND NOW() BETWEEN fecha_inicio AND fecha_fin";
    $stmt = $pdo->query($sql);
    $stats['votaciones_activas'] = $stmt->fetchColumn();
    
    $sql = "SELECT COUNT(*) as total FROM votos WHERE DATE(fecha_emision) = CURDATE()";
    $stmt = $pdo->query($sql);
    $stats['votos_hoy'] = $stmt->fetchColumn();
} catch(PDOException $e) {
    $error = "Error al obtener estadísticas: " . $e->getMessage();
}

log_activity("Acceso al panel de administrador");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Panel de Administración</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Usuarios Habilitados</h3>
                <p><?php echo $stats['usuarios_habilitados']; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Votaciones Activas</h3>
                <p><?php echo $stats['votaciones_activas']; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Votos Hoy</h3>
                <p><?php echo $stats['votos_hoy']; ?></p>
            </div>
        </div>
        
        <section class="admin-actions">
            <h2>Acciones de Administración</h2>
            
            <div class="actions-grid">
                <a href="gestion_usuarios.php" class="action-card">
                    <h3>Gestión de Usuarios</h3>
                    <p>Administrar usuarios habilitados para votar</p>
                </a>
                
                <a href="votaciones_diputados.php" class="action-card">
                    <h3>Votaciones de Diputados</h3>
                    <p>Crear y gestionar votaciones legislativas</p>
                </a>
                
                <a href="reportes.php" class="action-card">
                    <h3>Reportes y Estadísticas</h3>
                    <p>Generar reportes de votaciones</p>
                </a>
            </div>
        </section>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>