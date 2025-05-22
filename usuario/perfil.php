<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 3) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

// Obtener datos del usuario
$usuario = [];
try {
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener datos del usuario: " . $e->getMessage();
}

// Procesar actualización de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_perfil'])) {
    $email = clean_input($_POST['email']);
    
    try {
        $sql = "UPDATE usuarios SET email = :email WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':id' => $_SESSION['user_id']]);
        
        $_SESSION['email'] = $email;
        $success = "Perfil actualizado correctamente.";
    } catch(PDOException $e) {
        $error = "Error al actualizar el perfil: " . $e->getMessage();
    }
}

// Procesar cambio de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_password'])) {
    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];
    
    if (password_verify($password_actual, $usuario['contrasena'])) {
        if ($nueva_password === $confirmar_password) {
            $hashed_password = password_hash($nueva_password, PASSWORD_DEFAULT);
            
            try {
                $sql = "UPDATE usuarios SET contrasena = :password WHERE id_usuario = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':password' => $hashed_password, ':id' => $_SESSION['user_id']]);
                
                $success = "Contraseña cambiada correctamente.";
            } catch(PDOException $e) {
                $error = "Error al cambiar la contraseña: " . $e->getMessage();
            }
        } else {
            $error = "Las contraseñas nuevas no coinciden.";
        }
    } else {
        $error = "La contraseña actual es incorrecta.";
    }
}

log_activity("Accedió a la página de perfil");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
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

        .tab-container {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            border-bottom-color: var(--primary-color);
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }

            const tabs = document.getElementsByClassName("tab");
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
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
                <li><a href="perfil.php" class="active"><i class="fas fa-user"></i> Perfil</a></li>
                <li><a href="votaciones.php"><i class="fas fa-vote-yea"></i> Votaciones Activas</a></li>
                <li><a href="crear_votacion.php"><i class="fas fa-plus-circle"></i> Crear Votación</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Perfil de Usuario</h1>
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
            
            <div class="tab-container">
                <div class="tab active" onclick="openTab(event, 'datos-personales')">Datos Personales</div>
                <div class="tab" onclick="openTab(event, 'cambiar-password')">Cambiar Contraseña</div>
            </div>
            
            <div id="datos-personales" class="tab-content active">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido'] ?? ''); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="ci">CI:</label>
                        <input type="text" id="ci" name="ci" value="<?php echo htmlspecialchars($usuario['ci'] ?? ''); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required>
                    </div>
                    
                    <button type="submit" name="actualizar_perfil" class="btn btn-primary">Actualizar Perfil</button>
                </form>
            </div>
            
            <div id="cambiar-password" class="tab-content">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="password_actual">Contraseña Actual:</label>
                        <input type="password" id="password_actual" name="password_actual" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nueva_password">Nueva Contraseña:</label>
                        <input type="password" id="nueva_password" name="nueva_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" id="confirmar_password" name="confirmar_password" required>
                    </div>
                    
                    <button type="submit" name="cambiar_password" class="btn btn-primary">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>