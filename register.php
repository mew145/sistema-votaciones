<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = clean_input($_POST['nombre']);
    $apellido = clean_input($_POST['apellido']);
    $ci = clean_input($_POST['ci']);
    $fecha_nacimiento = clean_input($_POST['fecha_nacimiento']);
    $direccion = clean_input($_POST['direccion']);
    $email = clean_input($_POST['email']);
    $telefono = clean_input($_POST['telefono']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    $id_ciudad = clean_input($_POST['id_ciudad']);

    
    if ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        try {
            $sql = "SELECT id_usuario FROM usuarios WHERE email = :email OR ci = :ci";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email, ':ci' => $ci]);
            
            if ($stmt->rowCount() > 0) {
                $error = "El email o CI ya están registrados.";
            } else {
                $hashed_password = generate_hash($password);
                
                $sql = "INSERT INTO usuarios (id_usuario, nombre, apellido, ci, fecha_nacimiento, direccion, email, telefono, id_ciudad, id_rol, estado, contrasena) 
                        VALUES (UUID(), :nombre, :apellido, :ci, :fecha_nac, :direccion, :email, :telefono, :ciudad, 3, 'no habilitado', :contrasena)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':ci' => $ci,
                    ':fecha_nac' => $fecha_nacimiento,
                    ':direccion' => $direccion,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':ciudad' => $id_ciudad,
                    ':contrasena' => $hashed_password
                ]);
                
                $success = "Registro exitoso. Tu cuenta será habilitada por un administrador.";
            }
        } catch(PDOException $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}

$ciudades = [];
try {
    $sql = "SELECT id_ciudad, nombre FROM ciudades ORDER BY nombre";
    $stmt = $pdo->query($sql);
    $ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener ciudades: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Votaciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Registro de Usuario</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" pattern="^[A-Z][a-z]+$" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" pattern="^[A-Z][a-z]+$"  name="apellido" required>
                </div>
                
                <div class="form-group">
                    <label for="ci">Carnet de Identidad:</label>
                    <input type="text" id="ci"  name="ci" required>
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono">
                </div>
                
                <div class="form-group">
                    <label for="id_ciudad">Ciudad:</label>
                    <select id="id_ciudad" name="id_ciudad" required>
                        <option value="">-- Seleccione una ciudad --</option>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?php echo $ciudad['id_ciudad']; ?>"><?php echo htmlspecialchars($ciudad['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>
            
            <div class="login-link">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>
            </div>
        </div>
    </div>
</body>
</html>