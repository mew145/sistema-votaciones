<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votaciones</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistema de Votaciones</h1>
            <nav>
                <ul>
                    <?php if (is_logged_in()): ?>
                        <li><a href="<?php 
                            echo ($_SESSION['id_rol'] == 1) 
                            ? '../superadmin/dashboard.php' 
                            : (($_SESSION['id_rol'] == 2) 
                                ? '../admin/dashboard.php' 
                                : '../usuario/dashboard.php');
                        ?>">Inicio</a></li>
                        <li><a href="../logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="../index.php">Inicio</a></li>
                        <li><a href="../login.php">Iniciar Sesión</a></li>
                        <li><a href="../register.php">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main></main>