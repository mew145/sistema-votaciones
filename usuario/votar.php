<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de usuario
if (!is_logged_in() || $_SESSION['id_rol'] != 3) {
    header("Location: ../../login.php");
    exit();
}

// Verificar si se proporcionó un ID de elección
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$eleccion_id = clean_input($_GET['id']);
$eleccion = [];
$candidatos = [];
$error = '';
$success = '';

// Verificar si el usuario ya votó en esta elección
try {
    $sql = "SELECT id_voto FROM votos WHERE id_usuario = :usuario AND id_eleccion = :eleccion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $_SESSION['user_id'],
        ':eleccion' => $eleccion_id
    ]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Ya has votado en esta elección.";
    }
} catch(PDOException $e) {
    $error = "Error al verificar tu voto: " . $e->getMessage();
}

// Obtener información de la elección
$sql = "SELECT * FROM elecciones 
        WHERE id_eleccion = :id 
        AND estado = 'activa' 
        AND NOW() BETWEEN fecha_inicio AND fecha_fin";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $eleccion_id]);
$eleccion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$eleccion) {
    $error = "La elección no está disponible para votación en este momento.";
}

// Obtener candidatos para esta elección
if (!$error) {
    try {
        $sql = "SELECT c.*, p.nombre as partido, p.sigla 
                FROM candidatos c 
                JOIN partidos_politicos p ON c.id_partido = p.id_partido 
                WHERE c.id_eleccion = :eleccion";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eleccion' => $eleccion_id]);
        $candidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($candidatos)) {
            $error = "No hay candidatos disponibles para esta elección.";
        }
    } catch(PDOException $e) {
        $error = "Error al obtener candidatos: " . $e->getMessage();
    }
}

// Procesar el voto
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {
    $candidato_id = clean_input($_POST['candidato']);
    
    try {
        // Verificar que el candidato pertenece a esta elección
        $sql = "SELECT id_candidato FROM candidatos WHERE id_candidato = :candidato AND id_eleccion = :eleccion";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':candidato' => $candidato_id,
            ':eleccion' => $eleccion_id
        ]);
        
        if ($stmt->rowCount() == 1) {
            // Generar hash único para el voto
            $hash_voto = hash('sha256', $_SESSION['user_id'] . $eleccion_id . $candidato_id . time());
            
            // Registrar el voto
            $sql = "INSERT INTO votos (id_voto, id_usuario, id_eleccion, id_candidato, hash_voto) 
                    VALUES (UUID(), :usuario, :eleccion, :candidato, :hash)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario' => $_SESSION['user_id'],
                ':eleccion' => $eleccion_id,
                ':candidato' => $candidato_id,
                ':hash' => $hash_voto
            ]);
            
            // Registrar actividad
            log_activity("Votó en la elección: " . $eleccion['nombre']);
            
            $success = "¡Tu voto ha sido registrado con éxito!";
            $error = ''; // Limpiar cualquier error previo
        } else {
            $error = "Candidato no válido para esta elección.";
        }
    } catch(PDOException $e) {
        $error = "Error al registrar tu voto: " . $e->getMessage();
    }
}

// Registrar actividad
log_activity("Accedió a la página de votación: " . ($eleccion['nombre'] ?? 'Desconocida'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votar - <?php echo htmlspecialchars($eleccion['nombre'] ?? 'Elección'); ?></title>
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

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked + .candidato-content {
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 13px;
            background-color: rgba(52, 152, 219, 0.05);
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
            
            .candidatos-grid {
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="perfil.php"><i class="fas fa-user"></i> Perfil</a></li>
                <li><a href="votaciones.php"><i class="fas fa-vote-yea"></i> Votaciones Activas</a></li>
                <li><a href="crear_votacion.php"><i class="fas fa-plus-circle"></i> Crear Votación</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Votación: <?php echo htmlspecialchars($eleccion['nombre'] ?? ''); ?></h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
            </div>
        </div>
        
        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <a href="dashboard.php" class="btn btn-primary">Volver al panel</a>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <a href="dashboard.php" class="btn btn-primary">Volver al panel</a>
            <?php else: ?>
                <h2><?php echo htmlspecialchars($eleccion['nombre']); ?></h2>
                <p><?php echo htmlspecialchars($eleccion['descripcion']); ?></p>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $eleccion_id); ?>" method="post">
                    <div class="candidatos-grid">
                        <?php foreach ($candidatos as $candidato): ?>
                            <div class="candidato-card">
                                <label>
                                    <input type="radio" name="candidato" value="<?php echo $candidato['id_candidato']; ?>" required>
                                    <div class="candidato-content">
                                        <?php if ($candidato['foto_url']): ?>
                                            <img src="<?php echo htmlspecialchars($candidato['foto_url']); ?>" alt="<?php echo htmlspecialchars($candidato['nombre']); ?>">
                                        <?php else: ?>
                                            <div class="placeholder-img">
                                                <i class="fas fa-user-tie fa-3x"></i>
                                            </div>
                                        <?php endif; ?>
                                        <h3><?php echo htmlspecialchars($candidato['nombre'] . " " . $candidato['apellido']); ?></h3>
                                        <p><?php echo htmlspecialchars($candidato['partido'] . " (" . $candidato['sigla'] . ")"); ?></p>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Confirmar Voto</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>