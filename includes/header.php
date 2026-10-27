<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surco</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class= "main-header">
        <div class="header-container">
            <div class="header-left">
                <a href="index.php" class="logo">
                    <span class="logo-dot"></span>
                    <span class="logo-text">Surco</span>
                </a>
                <nav class="main-nav">
                    <a href="index.php" class="nav-link active"> Inicio </a>
                    <a href="discos.php" class="nav-link"> Discos </a>
                </nav>
            </div>
            <div class="header-actions">
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="perfil.php" class="btn-secondary">Perfil</a>
                <a href="logout.php" class="btn-secondary">Cerrar sesión</a>
            <?php else: ?>
                <a href="registro.php" class="btn-primary"> Crear cuenta </a>
                <a href="login.php" class="btn-secondary"> Iniciar sesión </a>
            <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="main-content">
    