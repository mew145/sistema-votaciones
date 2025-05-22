<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de administrador
if (!is_logged_in() || $_SESSION['id_rol'] != 2) {
    header("Location: ../../login.php");
    exit();
}

$error = '';
$success = '';
$votaciones = [];

// Procesar nueva votación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_votacion'])) {
    $nombre = clean_input($_POST['nombre']);
    $descripcion = clean_input($_POST['descripcion']);
    $fecha_inicio = clean_input($_POST['fecha_inicio']);
    $fecha_fin = clean_input($_POST['fecha_fin']);
    
    try {
        $sql = "INSERT INTO elecciones (id_eleccion, nombre, descripcion, fecha_inicio, fecha_fin, estado, id_tipo) 
                VALUES (UUID(), :nombre, :descripcion, :fecha_inicio, :fecha_fin, 'pendiente', 2)"; // Tipo 2 = Diputados
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin
        ]);
        
        // Registrar actividad
        log_activity("Creó votación de diputados: " . $nombre);
        
        $success = "Votación de diputados creada exitosamente. Debe ser aprobada por un superadministrador.";
    } catch(PDOException $e) {
        $error = "Error al crear votación: " . $e->getMessage();
    }
}

// Obtener votaciones de diputados
try {
    $sql = "SELECT * FROM elecciones WHERE id_tipo = 2 ORDER BY fecha_inicio DESC";
    $stmt = $pdo->query($sql);
    $votaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener votaciones: " . $e->getMessage();
}

// Registrar actividad
log_activity("Accedió a votaciones de diputados");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votaciones de Diputados</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Votaciones de Diputados</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <section class="nueva-votacion">
            <h2>Crear Nueva Votación</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de inicio:</label>
                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de cierre:</label>
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin" required>
                    </div>
                </div>
                
                <button type="submit" name="crear_votacion" class="btn btn-primary">Crear Votación</button>
            </form>
        </section>
        
        <section class="votaciones-list">
            <h2>Votaciones Existentes</h2>
            
            <?php if (empty($votaciones)): ?>
                <p>No hay votaciones de diputados registradas.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($votaciones as $votacion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($votacion['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($votacion['descripcion']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($votacion['fecha_inicio'])); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($votacion['fecha_fin'])); ?></td>
                                    <td>
                                        <span class="estado-badge estado-<?php echo $votacion['estado']; ?>">
                                            <?php echo ucfirst($votacion['estado']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>