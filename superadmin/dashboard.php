<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de superadministrador
if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Obtener estadísticas para el dashboard
$stats = [
    'total_usuarios' => 0,
    'total_admins' => 0,
    'votaciones_pendientes' => 0,
    'reclamos_abiertos' => 0
];

try {
    // Contar total de usuarios
    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $stmt = $pdo->query($sql);
    $stats['total_usuarios'] = $stmt->fetchColumn();
    
    // Contar total de administradores
    $sql = "SELECT COUNT(*) as total FROM admin";
    $stmt = $pdo->query($sql);
    $stats['total_admins'] = $stmt->fetchColumn();
    
    // Contar votaciones pendientes
    $sql = "SELECT COUNT(*) as total FROM elecciones WHERE estado = 'pendiente'";
    $stmt = $pdo->query($sql);
    $stats['votaciones_pendientes'] = $stmt->fetchColumn();
    
    // Contar reclamos abiertos (asumiendo que hay una tabla reclamos)
    $sql = "SELECT COUNT(*) as total FROM reclamos WHERE estado = 'abierto'";
    $stmt = $pdo->query($sql);
    $stats['reclamos_abiertos'] = $stmt->fetchColumn();
} catch(PDOException $e) {
    $error = "Error al obtener estadísticas: " . $e->getMessage();
}

// Registrar actividad
log_activity("Acceso al panel de superadministrador");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de SuperAdministrador</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .stat-card h3 {
            color: var(--dark-color);
            font-size: 16px;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .action-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            text-decoration: none;
            color: #333;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            color: var(--primary-color);
        }

        .action-card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .action-card p {
            color: #666;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .stats-grid, .actions-grid {
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
            <p>SuperAdministrador</p>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="gestion_administradores.php"><i class="fas fa-users-cog"></i> Administradores</a></li>
                <li><a href="votaciones_electorales.php"><i class="fas fa-vote-yea"></i> Votaciones Nacionales</a></li>
                <li><a href="votaciones_alcaldia.php"><i class="fas fa-landmark"></i> Votaciones Alcaldía</a></li>
                <li><a href="reportes_globales.php"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                <li><a href="reclamos.php"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Panel de SuperAdministración</h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
                <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
        
        <div class="card">
            <h2>Estadísticas del Sistema</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total de Usuarios</h3>
                    <p><?php echo $stats['total_usuarios']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Administradores</h3>
                    <p><?php echo $stats['total_admins']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Votaciones Pendientes</h3>
                    <p><?php echo $stats['votaciones_pendientes']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Reclamos Abiertos</h3>
                    <p><?php echo $stats['reclamos_abiertos']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Acciones Rápidas</h2>
            <div class="actions-grid">
                <a href="gestion_administradores.php" class="action-card">
                    <h3><i class="fas fa-users-cog"></i> Gestión de Administradores</h3>
                    <p>Administrar cuentas de administradores del sistema</p>
                </a>
                
                <a href="votaciones_electorales.php" class="action-card">
                    <h3><i class="fas fa-vote-yea"></i> Votaciones Electorales</h3>
                    <p>Configurar elecciones nacionales y asignar candidatos</p>
                </a>
                
                <a href="votaciones_alcaldia.php" class="action-card">
                    <h3><i class="fas fa-landmark"></i> Votaciones Alcaldía</h3>
                    <p>Crear votaciones municipales por ciudad</p>
                </a>
                
                <a href="reportes_globales.php" class="action-card">
                    <h3><i class="fas fa-chart-bar"></i> Reportes Globales</h3>
                    <p>Generar reportes detallados de todas las votaciones</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>