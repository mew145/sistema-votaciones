<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$votaciones = [];
$ciudades = [];

// Obtener lista de ciudades
try {
    $sql = "SELECT id_ciudad, nombre FROM ciudades ORDER BY nombre";
    $stmt = $pdo->query($sql);
    $ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener ciudades: " . $e->getMessage();
}

// Procesar creación de votación de alcaldía
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_votacion'])) {
    $nombre = clean_input($_POST['nombre']);
    $descripcion = clean_input($_POST['descripcion']);
    $fecha_inicio = clean_input($_POST['fecha_inicio']);
    $fecha_fin = clean_input($_POST['fecha_fin']);
    $ciudad_id = clean_input($_POST['ciudad_id']);
    
    try {
        $pdo->beginTransaction();
        
        // Primero crear la elección con tipo 2 (alcaldía)
        $sql = "INSERT INTO elecciones (id_eleccion, nombre, descripcion, fecha_inicio, fecha_fin, estado, id_tipo, id_ciudad) 
                VALUES (UUID(), :nombre, :descripcion, :fecha_inicio, :fecha_fin, 'pendiente', 2, :ciudad_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':ciudad_id' => $ciudad_id
        ]);
        
        $eleccion_id = $pdo->lastInsertId();
        
        // Registrar actividad
        log_activity("Creó votación de alcaldía: " . $nombre . " para ciudad ID: " . $ciudad_id);
        
        $pdo->commit();
        $success = "Votación de alcaldía creada exitosamente. Ahora puedes asignar candidatos.";
    } catch(PDOException $e) {
        $pdo->rollBack();
        $error = "Error al crear votación: " . $e->getMessage();
    }
}

// Procesar cambio de estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_estado'])) {
    $eleccion_id = clean_input($_POST['eleccion_id']);
    $nuevo_estado = clean_input($_POST['nuevo_estado']);
    
    try {
        $sql = "UPDATE elecciones SET estado = :estado WHERE id_eleccion = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado' => $nuevo_estado, ':id' => $eleccion_id]);
        
        log_activity("Cambió estado de votación de alcaldía ID: $eleccion_id a $nuevo_estado");
        
        $success = "Estado de la votación actualizado correctamente.";
    } catch(PDOException $e) {
        $error = "Error al actualizar estado: " . $e->getMessage();
    }
}

// Obtener votaciones de alcaldía existentes
try {
    $sql = "SELECT e.*, c.nombre as ciudad 
            FROM elecciones e
            JOIN ciudades c ON e.id_ciudad = c.id_ciudad
            WHERE e.id_tipo = 2
            ORDER BY e.fecha_inicio DESC";
    $stmt = $pdo->query($sql);
    $votaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener votaciones: " . $e->getMessage();
}

log_activity("Accedió a votaciones de alcaldía");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votaciones de Alcaldía</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark-color);
            color: white;
            position: fixed;
            height: 100%;
            padding: 20px 0;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: white;
            margin-bottom: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #bbb;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar-menu a:hover::before,
        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            transition: all 0.3s;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            color: var(--dark-color);
            margin: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="datetime-local"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        .estado-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .estado-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .estado-activa {
            background-color: #d4edda;
            color: #155724;
        }

        .estado-finalizada {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .inline-form {
            display: inline-block;
            margin-right: 5px;
        }

        .btn-small {
            padding: 4px 8px;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Votaciones</h3>
            <p>SuperAdministrador</p>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="gestion_administradores.php"><i class="fas fa-users-cog"></i> Administradores</a></li>
                <li><a href="votaciones_electorales.php"><i class="fas fa-vote-yea"></i> Votaciones Nacionales</a></li>
                <li><a href="votaciones_alcaldia.php" class="active"><i class="fas fa-landmark"></i> Votaciones Alcaldía</a></li>
                <li><a href="reportes_globales.php"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                <li><a href="reclamos.php"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Votaciones de Alcaldía</h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
                <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
        
        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <h2>Crear Nueva Votación de Alcaldía</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre de la votación:</label>
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
                
                <div class="form-group">
                    <label for="ciudad_id">Ciudad:</label>
                    <select id="ciudad_id" name="ciudad_id" required>
                        <option value="">-- Seleccione una ciudad --</option>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?php echo $ciudad['id_ciudad']; ?>"><?php echo htmlspecialchars($ciudad['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="crear_votacion" class="btn btn-primary">Crear Votación</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Votaciones de Alcaldía Existentes</h2>
            
            <?php if (empty($votaciones)): ?>
                <p>No hay votaciones de alcaldía registradas.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Ciudad</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($votaciones as $votacion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($votacion['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($votacion['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($votacion['ciudad']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($votacion['fecha_inicio'])); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($votacion['fecha_fin'])); ?></td>
                                    <td>
                                        <span class="estado-badge estado-<?php echo $votacion['estado']; ?>">
                                            <?php echo ucfirst($votacion['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inline-form">
                                            <input type="hidden" name="eleccion_id" value="<?php echo $votacion['id_eleccion']; ?>">
                                            <select name="nuevo_estado" required>
                                                <option value="pendiente" <?php echo ($votacion['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="activa" <?php echo ($votacion['estado'] == 'activa') ? 'selected' : ''; ?>>Activa</option>
                                                <option value="finalizada" <?php echo ($votacion['estado'] == 'finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                                            </select>
                                            <button type="submit" name="cambiar_estado" class="btn btn-small">Actualizar</button>
                                        </form>
                                        <a href="asignar_candidatos_alcaldia.php?id=<?php echo $votacion['id_eleccion']; ?>" class="btn btn-small btn-secondary">Asignar Candidatos</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>