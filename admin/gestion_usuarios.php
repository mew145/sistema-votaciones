<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de administrador
if (!is_logged_in() || $_SESSION['id_rol'] != 2) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$usuarios = [];

// Procesar cambio de estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_estado'])) {
    $usuario_id = clean_input($_POST['usuario_id']);
    $nuevo_estado = clean_input($_POST['nuevo_estado']);
    
    try {
        $sql = "UPDATE usuarios SET estado = :estado WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado' => $nuevo_estado, ':id' => $usuario_id]);
        
        // Registrar actividad
        log_activity("Cambió estado del usuario ID: $usuario_id a $nuevo_estado");
        
        $success = "Estado del usuario actualizado correctamente.";
    } catch(PDOException $e) {
        $error = "Error al actualizar estado: " . $e->getMessage();
    }
}

// Obtener lista de usuarios
try {
    $sql = "SELECT u.*, c.nombre as ciudad 
            FROM usuarios u 
            JOIN ciudades c ON u.id_ciudad = c.id_ciudad
            ORDER BY u.nombre, u.apellido";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener usuarios: " . $e->getMessage();
}

// Registrar actividad
log_activity("Accedió a gestión de usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Gestión de Usuarios</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
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
                            <td><?php echo htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['ci']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['ciudad']); ?></td>
                            <td>
                                <span class="estado-badge estado-<?php echo $usuario['estado']; ?>">
                                    <?php echo ucfirst($usuario['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inline-form">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id_usuario']; ?>">
                                    <select name="nuevo_estado" required>
                                        <option value="habilitado" <?php echo ($usuario['estado'] == 'habilitado') ? 'selected' : ''; ?>>Habilitado</option>
                                        <option value="no habilitado" <?php echo ($usuario['estado'] == 'no habilitado') ? 'selected' : ''; ?>>No Habilitado</option>
                                        <option value="nulo" <?php echo ($usuario['estado'] == 'nulo') ? 'selected' : ''; ?>>Nulo</option>
                                    </select>
                                    <button type="submit" name="cambiar_estado" class="btn btn-small">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>