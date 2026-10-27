<?php
// 1. Conexión a la base de datos y cabecera
require("bd/conexion_bd.php");
include("includes/header.php");

// 2. Capturar y sanitizar parámetros de la URL ($_GET)
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$orden  = isset($_GET['orden'])  ? $_GET['orden']  : 'novedades';

// 3. Construcción dinámica de la consulta SQL
$query = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio, IFNULL(a.nombre, 'Artista Desconocido') AS artista 
            FROM discos d 
            LEFT JOIN artistas a ON d.id_artista = a.id_artista";

// Filtro Búsqueda (WHERE)
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

// 4. Ejecutar la consulta en MariaDB
$resultado = mysqli_query($conexion, $query);
$total_discos = $resultado ? mysqli_num_rows($resultado) : 0;
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
        <?php if ($resultado && $total_discos > 0): ?>
            <?php while ($disco = mysqli_fetch_assoc($resultado)): ?>
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
mysqli_close($conexion);
include("includes/footer.php");
?>