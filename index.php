<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    redirect_based_on_role();
}

$votaciones_activas = [];
try {
    $sql = "SELECT e.*, COUNT(v.id_voto) as total_votos 
            FROM elecciones e 
            LEFT JOIN votos v ON e.id_eleccion = v.id_eleccion
            WHERE e.estado = 'activa' AND NOW() BETWEEN e.fecha_inicio AND e.fecha_fin
            GROUP BY e.id_eleccion
            ORDER BY e.fecha_inicio DESC
            LIMIT 3";
    $stmt = $pdo->query($sql);
    $votaciones_activas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener votaciones activas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votaciones Electorales</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <section class="hero">
            <h1>Sistema de Votaciones Electorales de Bolivia</h1>
            <p>Plataforma segura y transparente para procesos electorales</p>
            <div class="hero-actions">
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                <a href="register.php" class="btn btn-secondary">Registrarse</a>
            </div>
        </section>
        
        <?php if (!empty($votaciones_activas)): ?>
            <section class="votaciones-destacadas">
                <h2>Votaciones Activas</h2>
                <div class="votaciones-grid">
                    <?php foreach ($votaciones_activas as $votacion): ?>
                        <div class="votacion-card">
                            <h3><?php echo htmlspecialchars($votacion['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($votacion['descripcion']); ?></p>
                            <p>Total votos: <?php echo $votacion['total_votos']; ?></p>
                            <p>Finaliza: <?php echo date('d/m/Y H:i', strtotime($votacion['fecha_fin'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <section class="about">
            <h2>Sobre el Sistema</h2>
            <p>Este sistema garantiza la seguridad y transparencia de los procesos electorales en Bolivia, permitiendo:</p>
            <ul>
                <li>Votaciones presidenciales</li>
                <li>Elecciones legislativas</li>
                <li>Votaciones departamentales y municipales</li>
                <li>Consultas populares</li>
            </ul>
        </section>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>