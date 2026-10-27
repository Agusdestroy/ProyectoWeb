<?php
require("bd/conexion_bd.php");
include("includes/header.php");
?>

<section class="welcome-section">
    <h1 class="hero-title">Busca discos, artistas o listas</h1>
    <p class="hero-description">Empieza por un disco o un artista. Usa el buscador y encuentra tu próxima escucha en Surco.</p>

<div class="features-grid">
    <!-- Tarjeta 1: Buscador -->
    <div class="feature-card">
        <div class="feature-icon">
            <span>🔍</span>
        </div>
        <h2>Busca discos, artistas o listas</h2>
        <p>Usa el buscador para encontrar lo que quieres escuchar ahora. Puedes buscar por título, artista, género o cualquier palabra que te ayude a encontrar una referencia.</p>
    </div>

    <!-- Tarjeta 2: Uso sin inicio de sesión -->
    <div class="feature-card">
        <div class="feature-icon">
            <span>➔</span>
        </div>
        <h2>Usa Surco sin complicaciones</h2>
        <p>Explora sin necesidad de iniciar sesión. Cuando quieras guardar favoritos o seguir listas, podrás crear tu cuenta en cualquier momento.</p>
    </div>
</div>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>