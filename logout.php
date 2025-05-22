<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    log_activity("Cierre de sesión");
    
    session_unset();
    session_destroy();
}

header("Location: login.php");
exit();
?>