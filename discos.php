<?php
// 1. Conexión a la base de datos e inclusión de cabecera
require("bd/conexion_bd.php");
include("includes/header.php");

// 2. Capturar usuario en sesión (0 si no está logueado)
$id_usuario = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;

// 3. Capturar y sanitizar parámetros de la URL ($_GET)
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$orden  = isset($_GET['orden'])  ? $_GET['orden']  : 'novedades';

// 4. Consulta SQL con LEFT JOIN a la tabla 'favoritos'
$query = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio, 
                    IFNULL(a.nombre, 'Artista Desconocido') AS artista,
                    IF(f.id_disco IS NOT NULL, 1, 0) AS es_favorito
            FROM discos d 
            LEFT JOIN artistas a ON d.id_artista = a.id_artista
            LEFT JOIN favoritos f ON d.id_disco = f.id_disco AND f.id_usuario = $id_usuario";

// Filtro de Búsqueda (WHERE)
if (!empty($buscar)) {
    $buscar_clean = mysqli_real_escape_string($conexion, $buscar);
    $query .= " WHERE d.titulo LIKE '%$buscar_clean%' OR a.nombre LIKE '%$buscar_clean%'";
}

// Criterio de Ordenamiento (ORDER BY)
if ($orden === 'antiguos') {
    $query .= " ORDER BY d.fecha_lanzamiento ASC";
} elseif ($orden === 'populares') {
    $query .= " ORDER BY d.id_disco ASC";
} else {
    $query .= " ORDER BY d.fecha_lanzamiento DESC";
}

// 5. Ejecutar consulta
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$total_discos = mysqli_num_rows($resultado);
?>

<section class="explore-section">
    <span class="section-tag">ENCUENTRA TU PRÓXIMA OBSESIÓN</span>
    <h1 class="section-title">Explorar discos</h1>
    <p class="section-description">De clásicos que ya son tuyos a discos que todavía no conoces. Escucha más allá de lo de siempre.</p>

    <!-- Formulario de Búsqueda (GET) -->
    <form method="GET" action="discos.php" class="filter-search-form">
        <div class="filter-search-box">
            <span class="search-icon">🔍</span>
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($buscar); ?>" placeholder="Buscar por disco o artista...">
            <?php if (!empty($buscar)): ?>
                <a href="discos.php" class="clear-icon" title="Limpiar búsqueda">✕</a>
            <?php endif; ?>
            <button type="submit" style="display:none;"></button>
        </div>

        <div class="filter-pills-row">
            <div class="pills-left">
                <a href="discos.php?orden=novedades<?php echo !empty($buscar) ? '&buscar=' . urlencode($buscar) : ''; ?>" 
                    class="pill-btn <?php echo ($orden === 'novedades') ? 'active' : ''; ?>">Novedades</a>
                
                <a href="discos.php?orden=populares<?php echo !empty($buscar) ? '&buscar=' . urlencode($buscar) : ''; ?>" 
                    class="pill-btn <?php echo ($orden === 'populares') ? 'active' : ''; ?>">Populares</a>
                
                <a href="discos.php?orden=antiguos<?php echo !empty($buscar) ? '&buscar=' . urlencode($buscar) : ''; ?>" 
                    class="pill-btn <?php echo ($orden === 'antiguos') ? 'active' : ''; ?>">Más antiguos</a>
            </div>
            
            <?php if (!empty($buscar)): ?>
                <div class="pills-right">
                    <a href="discos.php" class="pill-btn">Ver todos los discos</a>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <p class="results-count">
        <?php if (!empty($buscar)): ?>
            Se encontraron <?php echo $total_discos; ?> resultados para "<strong><?php echo htmlspecialchars($buscar); ?></strong>"
        <?php else: ?>
            <?php echo $total_discos; ?> discos en el catálogo
        <?php endif; ?>
    </p>

    <!-- Grilla de Discos -->
    <div class="albums-grid">
        <?php if ($total_discos > 0): ?>
            <?php while ($disco = mysqli_fetch_assoc($resultado)): ?>
                <div class="album-card-wrapper">
                    <!-- Enlace a la vista detalle del disco -->
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

                    <!-- Botón de Favorito (Solo para usuarios con sesión activa) -->
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <form action="favorito_toggle.php" method="POST" class="fav-form">
                            <input type="hidden" name="id_disco" value="<?php echo $disco['id_disco']; ?>">
                            <button type="submit" class="btn-fav <?php echo ($disco['es_favorito'] == 1) ? 'active' : ''; ?>" title="<?php echo ($disco['es_favorito'] == 1) ? 'Quitar de favoritos' : 'Agregar a favoritos'; ?>">
                                <?php echo ($disco['es_favorito'] == 1) ? '♥' : '♡'; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; color: #9ca3af; padding: 20px 0;">
                No se encontraron discos que coincidan con la búsqueda.
            </p>
        <?php endif; ?>
    </div>

    <p class="grid-end-notice">Has llegado al final. Prueba un término de búsqueda para seguir descubriendo.</p>
</section>

<?php
// 6. Cierre de conexión y pie de página
mysqli_close($conexion);
include("includes/footer.php");
?>