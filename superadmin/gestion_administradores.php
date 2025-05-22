<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$administradores = [];
$usuarios = [];

// Procesamiento para crear administrador
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_admin'])) {
    $nombre = clean_input($_POST['nombre']);
    $apellido = clean_input($_POST['apellido']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    
    if ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        try {
            $sql = "SELECT id_admin FROM admin WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "El email ya está registrado.";
            } else {
                $hashed_password = generate_hash($password);
                
                $sql = "INSERT INTO admin (id_admin, nombre, apellido, email, contrasena, id_rol) 
                        VALUES (UUID(), :nombre, :apellido, :email, :contrasena, 2)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':email' => $email,
                    ':contrasena' => $hashed_password
                ]);
                
                log_activity("Creó nuevo administrador: " . $email);
                
                $success = "Administrador creado exitosamente.";
            }
        } catch(PDOException $e) {
            $error = "Error al crear administrador: " . $e->getMessage();
        }
    }
}

// Procesamiento para eliminar administrador
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_admin'])) {
    $admin_id = clean_input($_POST['admin_id']);
    
    try {
        if ($admin_id == $_SESSION['user_id']) {
            $error = "No puedes eliminarte a ti mismo.";
        } else {
            $sql = "DELETE FROM admin WHERE id_admin = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $admin_id]);
            
            log_activity("Eliminó administrador ID: " . $admin_id);
            
            $success = "Administrador eliminado exitosamente.";
        }
    } catch(PDOException $e) {
        $error = "Error al eliminar administrador: " . $e->getMessage();
    }
}

// Procesamiento para actualizar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_usuario'])) {
    $usuario_id = clean_input($_POST['usuario_id']);
    $nombre = clean_input($_POST['nombre']);
    $apellido = clean_input($_POST['apellido']);
    $email = clean_input($_POST['email']);
    $ci = clean_input($_POST['ci']);
    $estado = clean_input($_POST['estado']);
    
    try {
        // Verificar si el email o CI ya existen en otro usuario
        $sql = "SELECT id_usuario FROM usuarios WHERE (email = :email OR ci = :ci) AND id_usuario != :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':ci' => $ci, ':id' => $usuario_id]);
        
        if ($stmt->rowCount() > 0) {
            $error = "El email o CI ya están registrados por otro usuario.";
        } else {
            $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    email = :email, 
                    ci = :ci, 
                    estado = :estado 
                    WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':email' => $email,
                ':ci' => $ci,
                ':estado' => $estado,
                ':id' => $usuario_id
            ]);
            
            log_activity("Actualizó datos del usuario ID: " . $usuario_id);
            
            $success = "Usuario actualizado exitosamente.";
        }
    } catch(PDOException $e) {
        $error = "Error al actualizar usuario: " . $e->getMessage();
    }
}

// Obtener lista de administradores
try {
    $sql = "SELECT * FROM admin ORDER BY nombre, apellido";
    $stmt = $pdo->query($sql);
    $administradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener administradores: " . $e->getMessage();
}

// Obtener lista de usuarios
try {
    $sql = "SELECT u.*, c.nombre as ciudad_nombre 
            FROM usuarios u 
            JOIN ciudades c ON u.id_ciudad = c.id_ciudad 
            ORDER BY u.nombre, u.apellido";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener usuarios: " . $e->getMessage();
}

log_activity("Accedió a gestión de administradores y usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Administradores y Usuarios</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Estilos idénticos a votaciones_alcaldia.php */
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
            cursor: button;
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

        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e67e22;
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

        .estado-habilitado {
            background-color: #d4edda;
            color: #155724;
        }

        .estado-inhabilitado {
            background-color: #f8d7da;
            color: #721c24;
        }

        .inline-form {
            display: inline-block;
            margin-right: 5px;
        }

        .btn-small {
            padding: 4px 8px;
            font-size: 12px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
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
            
            .modal-content {
                width: 95%;
                margin: 20% auto;
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
                <li><a href="gestion_administradores.php" class="active"><i class="fas fa-users-cog"></i> Administradores</a></li>
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
            <h1>Gestión de Administradores y Usuarios</h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
                <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Crear Nuevo Administrador</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                    </div>
                </div>
                
                <button type="submit" name="crear_admin" class="btn btn-primary">Crear Administrador</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Administradores Existentes</h2>
            
            <?php if (empty($administradores)): ?>
                <p>No hay administradores registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($administradores as $admin): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($admin['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td>
                                        <?php if ($admin['id_admin'] != $_SESSION['user_id']): ?>
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inline-form">
                                                <input type="hidden" name="admin_id" value="<?php echo $admin['id_admin']; ?>">
                                                <button type="submit" name="eliminar_admin" class="btn btn-danger btn-small" onclick="return confirm('¿Estás seguro de eliminar este administrador?')">Eliminar</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">Tú</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Usuarios Registrados</h2>
            
            <?php if (empty($usuarios)): ?>
                <p>No hay usuarios registrados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>CI</th>
                                <th>Email</th>
                                <th>Ciudad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['ci']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['ciudad_nombre']); ?></td>
                                    <td>
                                        <span class="estado-badge estado-<?php echo $usuario['estado'] == 'habilitado' ? 'habilitado' : 'inhabilitado'; ?>">
                                            <?php echo $usuario['estado'] == 'habilitado' ? 'Habilitado' : 'Inhabilitado'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="abrirModalEditarUsuario(
                                            '<?php echo $usuario['id_usuario']; ?>',
                                            '<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($usuario['apellido'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($usuario['email'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($usuario['ci'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($usuario['estado'], ENT_QUOTES); ?>'
                                        )" class="btn btn-warning btn-small">Editar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal para editar usuario -->
    <div id="modalEditarUsuario" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalEditarUsuario()">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="formEditarUsuario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" id="usuario_id" name="usuario_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="modal_nombre">Nombre:</label>
                        <input type="text" id="modal_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_apellido">Apellido:</label>
                        <input type="text" id="modal_apellido" name="apellido" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="modal_email">Email:</label>
                        <input type="email" id="modal_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_ci">CI:</label>
                        <input type="text" id="modal_ci" name="ci" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="modal_estado">Estado:</label>
                    <select id="modal_estado" name="estado" required>
                        <option value="habilitado">Habilitado</option>
                        <option value="no habilitado">Inhabilitado</option>
                    </select>
                </div>
                
                <button type="submit" name="actualizar_usuario" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
    
    <script>
        // Funciones para manejar el modal de edición de usuario
        function abrirModalEditarUsuario(id, nombre, apellido, email, ci, estado) {
            document.getElementById('usuario_id').value = id;
            document.getElementById('modal_nombre').value = nombre;
            document.getElementById('modal_apellido').value = apellido;
            document.getElementById('modal_email').value = email;
            document.getElementById('modal_ci').value = ci;
            document.getElementById('modal_estado').value = estado;
            
            document.getElementById('modalEditarUsuario').style.display = 'block';
        }
        
        function cerrarModalEditarUsuario() {
            document.getElementById('modalEditarUsuario').style.display = 'none';
        }
        
        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('modalEditarUsuario');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>