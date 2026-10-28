<?php
// 1. Iniciar la sesión si aún no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Verificar estado de autenticación
$esta_logueado  = isset($_SESSION['id_usuario']);
$nombre_usuario = '';
$inicial        = '';

if ($esta_logueado) {
    // Captura el nombre guardado en sesión por login.php (soporta 'nombre' o 'usuario')
    if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
        $nombre_usuario = $_SESSION['nombre'];
    } elseif (isset($_SESSION['usuario'])) {
        $nombre_usuario = $_SESSION['usuario'];
    } else {
        $nombre_usuario = 'Usuario';
    }
    
    // Extrae la primera letra para el círculo de avatar
    $inicial = strtoupper(substr($nombre_usuario, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surco - Catálogo de Discos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <!-- Menú Izquierdo: Logo y Navegación Principal -->
            <div class="header-left">
                <a href="index.php" class="logo">
                    <span class="logo-icon">◎</span> surco
                </a>
                <nav class="main-nav">
                    <a href="index.php" class="nav-link">Inicio</a>
                    <a href="discos.php" class="nav-link">Discos</a>
                    
                    <?php if ($esta_logueado): ?>
                        <a href="listas.php" class="nav-link">Listas</a>
                        <a href="coleccion.php" class="nav-link">Mi colección</a>
                    <?php endif; ?>
                </nav>
            </div>
            <!-- Menú Derecho: Estado de Sesión -->
            <div class="header-right">
                <?php if ($esta_logueado): ?>
                    <!-- Enlace al perfil de usuario -->
                    <a href="perfil.php" class="user-badge" title="Ir a mi perfil">
                        <span class="user-avatar"><?php echo htmlspecialchars($inicial); ?></span>
                        <span class="user-name"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                    </a>
                    <a href="logout.php" class="logout-link" title="Cerrar sesión">Salir</a>
                <?php else: ?>
                    <a href="login.php" class="btn-secondary">Iniciar Sesión</a>
                    <a href="registro.php" class="btn-primary">Crear Cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="site-main">