<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    redirect_based_on_role();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    
    try {
        $sql = "SELECT id_usuario as id, email, contrasena, id_rol, nombre, apellido, estado 
                FROM usuarios WHERE email = :email
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $sql = "SELECT id_admin as id, email, contrasena, id_rol, nombre, apellido, 'habilitado' as estado 
                    FROM admin WHERE email = :email
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if (!$user) {
            $sql = "SELECT id_super_admin as id, email, contrasena, id_rol, nombre, apellido, 'habilitado' as estado 
                    FROM super_admin WHERE email = :email
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if ($user && verify_hash($password, $user['contrasena'])) {
            if (isset($user['estado']) && $user['estado'] != 'habilitado') {
                $error = "Tu cuenta no está habilitada para votar.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['id_rol'] = $user['id_rol'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellido'] = $user['apellido'];
                
                
                redirect_based_on_role();
            }
        } else {
            $error = "Email o contraseña incorrectos.";
        }
    } catch(PDOException $e) {
        $error = "Error al iniciar sesión: " . $e->getMessage();
    }
}

function redirect_based_on_role() {
    switch ($_SESSION['id_rol']) {
        case 1: // SuperAdmin
            header("Location: superadmin/dashboard.php");
            break;
        case 2: // Admin
            header("Location: admin/dashboard.php");
            break;
        case 3: // Usuario
            header("Location: usuario/dashboard.php");
            break;
        default:
            header("Location: login.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votaciones - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Sistema de Votaciones Electorales</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="login-link">
                ¿No tienes una cuenta? <a href="register.php">Registrate</a>
            </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>