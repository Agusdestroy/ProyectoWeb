<?php
// 1. Iniciar sesión y validar autenticación
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require("bd/conexion_bd.php");

$id_usuario     = (int)$_SESSION['id_usuario'];
$nombre_usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'USUARIO';

// Determinar qué vista mostrar ('favoritos' o 'listas')
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'favoritos';

// 2. Contadores para las pestañas superiores
$res_total_fav = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM favoritos WHERE id_usuario = $id_usuario");
$fila_fav       = mysqli_fetch_assoc($res_total_fav);
$total_fav      = $fila_fav['total'];

$res_total_listas = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM listas WHERE id_usuario = $id_usuario");
$fila_listas      = mysqli_fetch_assoc($res_total_listas);
$total_listas     = $fila_listas['total'];

// 3. Consultas directas según la pestaña seleccionada
if ($vista === 'listas') {
    // Consulta para la pestaña 'Mis listas'
    $query = "SELECT l.id_lista, l.titulo, l.descripcion, COUNT(ld.id_disco) AS total_discos
              FROM listas l
              LEFT JOIN lista_discos ld ON l.id_lista = ld.id_lista
              WHERE l.id_usuario = $id_usuario
              GROUP BY l.id_lista 
              ORDER BY l.fecha_creacion DESC";
} else {
    // Consulta para la pestaña 'Favoritos'
    $query = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio,
                     IFNULL(a.nombre, 'Artista Desconocido') AS artista, f.fecha_agregado
              FROM favoritos f
              INNER JOIN discos d ON f.id_disco = d.id_disco
              LEFT JOIN artistas a ON d.id_artista = a.id_artista
              WHERE f.id_usuario = $id_usuario
              ORDER BY f.fecha_agregado DESC";
}

$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$total_mostrados = mysqli_num_rows($resultado);

include("includes/header.php");
?>

<section class="explore-section">

    <!-- Encabezado con Botón -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
        <div>
            <span class="section-tag"><?php echo strtoupper(htmlspecialchars($nombre_usuario)); ?> · TU LADO DEL SURCO</span>
            <h1 class="section-title" style="margin-top: 4px;">Mi colección</h1>
            <p class="section-description">
                <?php echo ($vista === 'listas') ? "$total_listas listas creadas. Tu selección musical organizada." : "$total_fav discos favoritos. Esos a los que siempre vuelves."; ?>
            </p>
        </div>
        
        <?php if ($vista === 'listas'): ?>
            <a href="crear_lista.php" class="btn-primary" style="display: flex; align-items: center; gap: 6px;">
                <span>+</span> Crear una lista
            </a>
        <?php else: ?>
            <a href="discos.php" class="btn-primary" style="display: flex; align-items: center; gap: 6px;">
                <span>+</span> Explorar más discos
            </a>
        <?php endif; ?>
    </div>

    <!-- Pestañas de Navegación -->
    <div class="filter-pills-row" style="margin-bottom: 25px;">
        <div class="pills-left">
            <a href="mi_coleccion.php?vista=favoritos" class="pill-btn <?php echo ($vista !== 'listas') ? 'active' : ''; ?>">
                ♡ Favoritos · <?php echo $total_fav; ?>
            </a>
            <a href="mi_coleccion.php?vista=listas" class="pill-btn <?php echo ($vista === 'listas') ? 'active' : ''; ?>">
                Mis listas · <?php echo $total_listas; ?>
            </a>
        </div>
    </div>

    <!-- RENDERIZADO: Vista Favoritos vs Vista Listas -->
    <?php if ($vista === 'listas'): ?>

        <!-- VISTA DE LISTAS -->
        <p class="results-count" style="margin-bottom: 20px;">
            Mostrando <?php echo $total_mostrados; ?> listas
        </p>

        <?php if ($total_mostrados > 0): ?>
            <div class="profile-lists">
                <?php while ($lista = mysqli_fetch_assoc($resultado)): ?>
                    <?php
                    $id_lista = $lista['id_lista'];
                    $query_portadas = "SELECT d.titulo, d.portada_url
                                        FROM lista_discos ld
                                        JOIN discos d ON ld.id_disco = d.id_disco
                                        WHERE ld.id_lista = $id_lista
                                        LIMIT 3";
                    $res_portadas = mysqli_query($conexion, $query_portadas);
                    ?>
                    <div class="list-card">
                        <a href="lista_detalle.php?id=<?php echo $id_lista; ?>" style="text-decoration: none; color: inherit;">
                            <div class="list-covers">
                                <?php while ($portada = mysqli_fetch_assoc($res_portadas)): ?>
                                    <img src="<?php echo htmlspecialchars($portada['portada_url']); ?>" alt="<?php echo htmlspecialchars($portada['titulo']); ?>">
                                <?php endwhile; ?>
                            </div>
                            <h3 class="list-title"><?php echo htmlspecialchars($lista['titulo']); ?></h3>
                            <?php if (!empty($lista['descripcion'])): ?>
                                <p class="list-description"><?php echo htmlspecialchars($lista['descripcion']); ?></p>
                            <?php endif; ?>
                            <p class="list-meta"><?php echo $lista['total_discos']; ?> discos</p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="color: #9ca3af; padding: 30px 0;">
                Aún no has creado ninguna lista. <a href="crear_lista.php" style="color: #22c55e;">Crea tu primera lista</a>.
            </p>
        <?php endif; ?>

    <?php else: ?>

        <!-- VISTA DE FAVORITOS (Grilla de discos) -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <p class="results-count" style="margin: 0;">Mostrando los <?php echo $total_mostrados; ?> favoritos</p>
            <a href="perfil.php" class="rec-link" style="font-size: 0.85rem;">Editar mis 4 destacados →</a>
        </div>

        <div class="albums-grid">
            <?php if ($total_mostrados > 0): ?>
                <?php while ($disco = mysqli_fetch_assoc($resultado)): ?>
                    <div class="album-card-wrapper">
                        <a href="disco_detalle.php?id=<?php echo $disco['id_disco']; ?>" class="album-card-link">
                            <div class="album-card">
                                <div class="cover-wrapper">
                                    <img src="<?php echo htmlspecialchars($disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($disco['titulo']); ?>">
                                </div>
                                <h3 class="album-title"><?php echo htmlspecialchars($disco['titulo']); ?></h3>
                                <p class="album-artist">
                                    <?php echo htmlspecialchars($disco['artista']); ?> · <?php echo htmlspecialchars($disco['anio']); ?>
                                </p>
                            </div>
                        </a>

                        <form action="favorito_toggle.php" method="POST" class="fav-form">
                            <input type="hidden" name="id_disco" value="<?php echo $disco['id_disco']; ?>">
                            <input type="hidden" name="origen" value="mi_coleccion.php">
                            <button type="submit" class="btn-fav active" title="Quitar de mi colección">♥</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; color: #9ca3af; padding: 30px 0;">
                    No se encontraron discos en tu colección. <a href="discos.php" style="color: #22c55e;">Explora el catálogo</a>.
                </p>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>