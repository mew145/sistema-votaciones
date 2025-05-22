<?php
require_once('../includes/config.php');  
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 3) {
    header("Location: ../login.php");
    exit();
}

$votaciones = [];
$ciudad_usuario = '';

try {
    $sql = "SELECT id_ciudad FROM usuarios WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $ciudad_usuario = $user['id_ciudad'];
        
        // Consulta modificada para obtener votaciones activas sin depender de la tabla reportes
        $sql = "SELECT e.* FROM elecciones e 
        WHERE e.estado = 'activa' 
        AND NOW() BETWEEN e.fecha_inicio AND e.fecha_fin";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $votaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Depuración
        error_log("Votaciones encontradas: " . count($votaciones));
        foreach($votaciones as $v) {
            error_log("Votación: " . $v['nombre'] . " - " . $v['fecha_inicio'] . " a " . $v['fecha_fin']);
        }
    }
} catch(PDOException $e) {
    $error = "Error al obtener votaciones: " . $e->getMessage();
}

log_activity("Acceso al panel de usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
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

        .votaciones-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .votacion-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .votacion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .votacion-card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .votacion-card p {
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
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

        .form-group textarea {
            min-height: 100px;
        }

        .opcion-group {
            display: flex;
            margin-bottom: 10px;
        }

        .opcion-group input {
            flex: 1;
            margin-right: 10px;
        }

        .btn-remove {
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0 12px;
            cursor: pointer;
        }

        .candidatos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .candidato-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s;
        }

        .candidato-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .candidato-card label {
            display: block;
            cursor: pointer;
        }

        .candidato-content {
            text-align: center;
        }

        .candidato-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .placeholder-img {
            width: 100%;
            height: 200px;
            background-color: #eee;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .btn-block {
            display: block;
            width: 100%;
            padding: 12px;
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
            
            .votaciones-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Votaciones</h3>
            <p>Panel de Usuario</p>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="perfil.php"><i class="fas fa-user"></i> Perfil</a></li>
                <li><a href="votaciones.php"><i class="fas fa-vote-yea"></i> Votaciones Activas</a></li>
                <li><a href="crear_votacion.php"><i class="fas fa-plus-circle"></i> Crear Votación</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Votaciones Activas</h2>
            </div>
            <div class="card-body">
                <?php if (empty($votaciones)): ?>
                    <div class="alert alert-info">No hay votaciones activas en tu ciudad en este momento.</div>
                <?php else: ?>
                    <div class="votaciones-grid">
                        <?php foreach ($votaciones as $votacion): ?>
                            <div class="votacion-card">
                                <h3><?php echo htmlspecialchars($votacion['nombre']); ?></h3>
                                <p><?php echo htmlspecialchars($votacion['descripcion']); ?></p>
                                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($votacion['fecha_inicio'])); ?> - <?php echo date('d/m/Y H:i', strtotime($votacion['fecha_fin'])); ?></p>
                                <a href="votar.php?id=<?php echo $votacion['id_eleccion']; ?>" class="btn btn-primary">Participar</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Crear Votación Personalizada</h2>
            </div>
            <div class="card-body">
                <p>Puedes crear una votación sencilla para uso personal o de grupo.</p>
                <a href="crear_votacion.php" class="btn btn-secondary">Crear Nueva Votación</a>
            </div>
        </div>
    </div>
</body>
</html>