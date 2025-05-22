<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de superadministrador
if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$reclamos = [];

// Procesar resolución de reclamo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resolver_reclamo'])) {
    $reclamo_id = clean_input($_POST['reclamo_id']);
    $solucion = clean_input($_POST['solucion']);
    
    try {
        $sql = "UPDATE reclamos SET estado = 'resuelto', solucion = :solucion, fecha_resolucion = NOW() WHERE id_reclamo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':solucion' => $solucion, ':id' => $reclamo_id]);
        
        // Registrar actividad
        log_activity("Resolvió reclamo ID: " . $reclamo_id);
        
        $success = "Reclamo resuelto exitosamente.";
    } catch(PDOException $e) {
        $error = "Error al resolver reclamo: " . $e->getMessage();
    }
}

// Obtener reclamos
try {
    $sql = "SELECT r.*, u.nombre, u.apellido, u.ci 
            FROM reclamos r
            JOIN usuarios u ON r.id_usuario = u.id_usuario
            ORDER BY r.fecha_creacion DESC";
    $stmt = $pdo->query($sql);
    $reclamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener reclamos: " . $e->getMessage();
}

// Registrar actividad
log_activity("Accedió al panel de reclamos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reclamos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Panel de Reclamos</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="reclamos-list">
            <?php if (empty($reclamos)): ?>
                <p>No hay reclamos pendientes.</p>
            <?php else: ?>
                <?php foreach ($reclamos as $reclamo): ?>
                    <div class="reclamo-card <?php echo $reclamo['estado']; ?>">
                        <div class="reclamo-header">
                            <h3>Reclamo de <?php echo htmlspecialchars($reclamo['nombre'] . " " . $reclamo['apellido']); ?> (CI: <?php echo htmlspecialchars($reclamo['ci']); ?>)</h3>
                            <span class="estado-badge estado-<?php echo $reclamo['estado']; ?>">
                                <?php echo ucfirst($reclamo['estado']); ?>
                            </span>
                        </div>
                        
                        <div class="reclamo-body">
                            <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($reclamo['fecha_creacion'])); ?></p>
                            <p><strong>Motivo:</strong> <?php echo htmlspecialchars($reclamo['motivo']); ?></p>
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($reclamo['descripcion']); ?></p>
                            
                            <?php if ($reclamo['estado'] == 'resuelto'): ?>
                                <div class="reclamo-solucion">
                                    <p><strong>Solución:</strong> <?php echo htmlspecialchars($reclamo['solucion']); ?></p>
                                    <p><strong>Fecha resolución:</strong> <?php echo date('d/m/Y H:i', strtotime($reclamo['fecha_resolucion'])); ?></p>
                                </div>
                            <?php else: ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="reclamo-form">
                                    <input type="hidden" name="reclamo_id" value="<?php echo $reclamo['id_reclamo']; ?>">
                                    <div class="form-group">
                                        <label for="solucion">Solución:</label>
                                        <textarea id="solucion" name="solucion" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" name="resolver_reclamo" class="btn btn-primary">Resolver Reclamo</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>