<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de usuario
if (!is_logged_in() || $_SESSION['id_rol'] != 3) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = clean_input($_POST['nombre']);
    $descripcion = clean_input($_POST['descripcion']);
    $fecha_fin = clean_input($_POST['fecha_fin']);
    $opciones = array_filter($_POST['opciones'], function($opcion) {
        return !empty(trim($opcion));
    });
    
    // Validaciones
    if (empty($nombre) || empty($descripcion) || empty($fecha_fin)) {
        $error = "Todos los campos son requeridos.";
    } elseif (count($opciones) < 2) {
        $error = "Debe proporcionar al menos 2 opciones.";
    } else {
        try {
            // Iniciar transacción
            $pdo->beginTransaction();
            
            // Insertar la elección
            $sql = "INSERT INTO elecciones (id_eleccion, nombre, descripcion, fecha_inicio, fecha_fin, estado, id_tipo) 
                    VALUES (UUID(), :nombre, :descripcion, NOW(), :fecha_fin, 'activa', 3)"; // Tipo 3 = Votación simple
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':fecha_fin' => $fecha_fin
            ]);
            
            $eleccion_id = $pdo->lastInsertId();
            
            // Insertar opciones (como candidatos)
            foreach ($opciones as $opcion) {
                $sql = "INSERT INTO candidatos (id_candidato, id_eleccion, nombre, apellido, id_partido) 
                        VALUES (UUID(), :eleccion, :opcion, '', '000')"; // Partido 000 = Sin partido
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':eleccion' => $eleccion_id, ':opcion' => $opcion]);
            }
            
            // Registrar actividad
            log_activity("Creó una votación simple: " . $nombre);
            
            $pdo->commit();
            $success = "Votación creada exitosamente. Comparte el ID: " . $eleccion_id;
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error = "Error al crear la votación: " . $e->getMessage();
        }
    }
}

// Registrar actividad
log_activity("Accedió a crear votación simple");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Votación Simple</title>
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

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function addOpcion() {
            const container = document.getElementById('opciones-container');
            const div = document.createElement('div');
            div.className = 'opcion-group';
            div.innerHTML = `
                <input type="text" name="opciones[]" required>
                <button type="button" class="btn btn-remove" onclick="removeOpcion(this)">-</button>
            `;
            container.appendChild(div);
        }

        function removeOpcion(button) {
            const container = document.getElementById('opciones-container');
            if (container.children.length > 2) {
                button.parentElement.remove();
            } else {
                alert('Debe haber al menos 2 opciones');
            }
        }
    </script>
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
                <li><a href="crear_votacion.php" class="active"><i class="fas fa-plus-circle"></i> Crear Votación</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Crear Votación Simple</h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
            </div>
        </div>
        
        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre de la votación:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="fecha_fin">Fecha de cierre:</label>
                    <input type="datetime-local" id="fecha_fin" name="fecha_fin" required>
                </div>
                
                <div class="form-group">
                    <label>Opciones de votación:</label>
                    <div id="opciones-container">
                        <div class="opcion-group">
                            <input type="text" name="opciones[]" required>
                            <button type="button" class="btn btn-remove" onclick="removeOpcion(this)">-</button>
                        </div>
                        <div class="opcion-group">
                            <input type="text" name="opciones[]" required>
                            <button type="button" class="btn btn-remove" onclick="removeOpcion(this)">-</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addOpcion()">+ Añadir Opción</button>
                </div>
                
                <button type="submit" class="btn btn-primary">Crear Votación</button>
            </form>
        </div>
    </div>
</body>
</html>