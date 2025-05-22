<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$eleccion_id = isset($_GET['id']) ? clean_input($_GET['id']) : '';
$eleccion = [];
$candidatos_asignados = [];
$candidatos_disponibles = [];
$partidos = [];

// Obtener información de la elección
try {
    $sql = "SELECT * FROM elecciones WHERE id_eleccion = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $eleccion_id]);
    $eleccion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$eleccion) {
        $error = "Elección no encontrada.";
    }
} catch(PDOException $e) {
    $error = "Error al obtener información de la elección: " . $e->getMessage();
}

// Obtener candidatos asignados a esta elección
try {
    $sql = "SELECT c.*, p.nombre as partido, p.sigla 
            FROM candidatos c
            JOIN partidos_politicos p ON c.id_partido = p.id_partido
            WHERE c.id_eleccion = :eleccion_id
            ORDER BY c.apellido, c.nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':eleccion_id' => $eleccion_id]);
    $candidatos_asignados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener candidatos asignados: " . $e->getMessage();
}

// Obtener candidatos disponibles (sin asignar o asignados a otras elecciones)
try {
    $sql = "SELECT c.*, p.nombre as partido, p.sigla 
            FROM candidatos c
            JOIN partidos_politicos p ON c.id_partido = p.id_partido
            WHERE c.id_eleccion IS NULL OR c.id_eleccion != :eleccion_id
            ORDER BY c.apellido, c.nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':eleccion_id' => $eleccion_id]);
    $candidatos_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener candidatos disponibles: " . $e->getMessage();
}

// Obtener lista de partidos para agregar nuevo candidato
try {
    $sql = "SELECT * FROM partidos_politicos ORDER BY nombre";
    $stmt = $pdo->query($sql);
    $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener partidos políticos: " . $e->getMessage();
}

// Procesar asignación de candidato existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asignar_candidato'])) {
    $candidato_id = clean_input($_POST['candidato_id']);
    
    try {
        $sql = "UPDATE candidatos SET id_eleccion = :eleccion_id WHERE id_candidato = :candidato_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eleccion_id' => $eleccion_id,
            ':candidato_id' => $candidato_id
        ]);
        
        log_activity("Asignó candidato ID: $candidato_id a elección ID: $eleccion_id");
        
        $success = "Candidato asignado exitosamente.";
        header("Location: asignar_candidatos.php?id=" . $eleccion_id);
        exit();
    } catch(PDOException $e) {
        $error = "Error al asignar candidato: " . $e->getMessage();
    }
}

// Procesar remoción de candidato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remover_candidato'])) {
    $candidato_id = clean_input($_POST['candidato_id']);
    
    try {
        $sql = "UPDATE candidatos SET id_eleccion = NULL WHERE id_candidato = :candidato_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':candidato_id' => $candidato_id]);
        
        log_activity("Removió candidato ID: $candidato_id de elección ID: $eleccion_id");
        
        $success = "Candidato removido exitosamente.";
        header("Location: asignar_candidatos.php?id=" . $eleccion_id);
        exit();
    } catch(PDOException $e) {
        $error = "Error al remover candidato: " . $e->getMessage();
    }
}

// Procesar creación de nuevo candidato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_candidato'])) {
    $nombre = clean_input($_POST['nombre']);
    $apellido = clean_input($_POST['apellido']);
    $partido_id = clean_input($_POST['partido_id']);
    
    // Manejo de la foto
    $foto_url = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/candidatos/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Verificar si es una imagen real
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            // Generar nombre único para el archivo
            $new_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto_url = $target_file;
            }
        }
    }
    
    try {
        $sql = "INSERT INTO candidatos (id_candidato, id_eleccion, nombre, apellido, id_partido, foto_url) 
                VALUES (UUID(), :eleccion_id, :nombre, :apellido, :partido_id, :foto_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eleccion_id' => $eleccion_id,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':partido_id' => $partido_id,
            ':foto_url' => $foto_url
        ]);
        
        log_activity("Creó nuevo candidato para elección ID: $eleccion_id");
        
        $success = "Candidato creado y asignado exitosamente.";
        header("Location: asignar_candidatos.php?id=" . $eleccion_id);
        exit();
    } catch(PDOException $e) {
        $error = "Error al crear candidato: " . $e->getMessage();
    }
}

log_activity("Accedió a asignar candidatos para elección ID: $eleccion_id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Candidatos</title>
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

        .candidato-foto {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .foto-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 20px;
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
                <li><a href="votaciones_alcaldia.php"><i class="fas fa-landmark"></i> Votaciones Alcaldía</a></li>
                <li><a href="reportes_globales.php"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                <li><a href="reclamos.php"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Asignar Candidatos</h1>
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
            
            <h2>Elección: <?php echo htmlspecialchars($eleccion['nombre'] ?? 'No encontrada'); ?></h2>
            <p><?php echo htmlspecialchars($eleccion['descripcion'] ?? ''); ?></p>
            <p><strong>Estado:</strong> 
                <span class="estado-badge estado-<?php echo $eleccion['estado'] ?? 'pendiente'; ?>">
                    <?php echo ucfirst($eleccion['estado'] ?? 'pendiente'); ?>
                </span>
            </p>
        </div>
        
        <div class="card">
            <h2>Candidatos Asignados</h2>
            
            <?php if (empty($candidatos_asignados)): ?>
                <p>No hay candidatos asignados a esta elección.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Partido</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatos_asignados as $candidato): ?>
                                <tr>
                                    <td>
                                        <?php if ($candidato['foto_url']): ?>
                                            <img src="<?php echo htmlspecialchars($candidato['foto_url']); ?>" alt="Foto" class="candidato-foto">
                                        <?php else: ?>
                                            <div class="foto-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($candidato['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($candidato['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($candidato['partido'] . ' (' . $candidato['sigla'] . ')'); ?></td>
                                    <td>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $eleccion_id; ?>" method="post" class="inline-form">
                                            <input type="hidden" name="candidato_id" value="<?php echo $candidato['id_candidato']; ?>">
                                            <button type="submit" name="remover_candidato" class="btn btn-danger btn-small" onclick="return confirm('¿Estás seguro de remover este candidato?')">Remover</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Asignar Candidato Existente</h2>
            
            <?php if (empty($candidatos_disponibles)): ?>
                <p>No hay candidatos disponibles para asignar.</p>
            <?php else: ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $eleccion_id; ?>" method="post">
                    <div class="form-group">
                        <label for="candidato_id">Seleccionar Candidato:</label>
                        <select name="candidato_id" id="candidato_id" required>
                            <option value="">-- Seleccione un candidato --</option>
                            <?php foreach ($candidatos_disponibles as $candidato): ?>
                                <option value="<?php echo $candidato['id_candidato']; ?>">
                                    <?php echo htmlspecialchars($candidato['apellido'] . ', ' . $candidato['nombre'] . ' (' . $candidato['partido'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="asignar_candidato" class="btn btn-primary">Asignar Candidato</button>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Crear Nuevo Candidato</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $eleccion_id; ?>" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="partido_id">Partido:</label>
                    <select name="partido_id" id="partido_id" required>
                        <option value="">-- Seleccione un partido --</option>
                        <?php foreach ($partidos as $partido): ?>
                            <option value="<?php echo $partido['id_partido']; ?>">
                                <?php echo htmlspecialchars($partido['nombre'] . ' (' . $partido['sigla'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto del Candidato:</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>
                
                <button type="submit" name="crear_candidato" class="btn btn-primary">Crear y Asignar Candidato</button>
            </form>
        </div>
    </div>
</body>
</html>