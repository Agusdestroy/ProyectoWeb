<?php
// Habilitar visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Conexión a la base de datos PRIMERO (sin enviar HTML todavía)
require("bd/conexion_bd.php");

// 2. Captura y validación del parámetro ID por GET
$id_disco = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_disco <= 0) {
    header("Location: discos.php");
    exit();
}

// 3. Consulta SQL del disco
$query_disco = "SELECT d.id_disco, d.id_artista, d.titulo, d.portada_url, d.fecha_lanzamiento, d.descripcion, d.genero, d.sello, 
                       YEAR(d.fecha_lanzamiento) AS anio,
                       DATE_FORMAT(d.fecha_lanzamiento, '%d/%m/%Y') AS fecha_formateada,
                       IFNULL(a.nombre, 'Artista Desconocido') AS artista 
                FROM discos d 
                LEFT JOIN artistas a ON d.id_artista = a.id_artista 
                WHERE d.id_disco = $id_disco";

$res_disco = mysqli_query($conexion, $query_disco);

if (!$res_disco) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$disco = mysqli_fetch_assoc($res_disco);

// Redireccionar si el disco no existe en la base de datos
if (!$disco) {
    header("Location: discos.php");
    exit();
}

// 4. Consulta SQL de las canciones correspondientes al disco
$query_canciones = "SELECT numero_pista, titulo, duracion 
                    FROM canciones 
                    WHERE id_disco = $id_disco 
                    ORDER BY numero_pista ASC";

$res_canciones = mysqli_query($conexion, $query_canciones);
$total_canciones = $res_canciones ? mysqli_num_rows($res_canciones) : 0;

// 5. Incluir la cabecera HTML únicamente tras validar los datos
include("includes/header.php");
?>

<section class="album-detail-container">
    <!-- Migas de pan (Breadcrumb) -->
    <nav class="breadcrumb">
        <a href="discos.php">Discos</a> / 
        <a href="artista_detalle.php?id=<?php echo $disco['id_artista']; ?>"><?php echo htmlspecialchars($disco['artista']); ?></a> / 
        <span class="breadcrumb-active"><?php echo htmlspecialchars($disco['titulo']); ?></span>
    </nav>

    <!-- Encabezado principal del disco -->
    <div class="album-header">
        <div class="album-cover-big">
            <img src="<?php echo htmlspecialchars($disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($disco['titulo']); ?>">
        </div>
        
        <div class="album-info-content">
            <span class="album-tag">ÁLBUM - <?php echo htmlspecialchars($disco['anio']); ?></span>
            <h1 class="album-main-title"><?php echo htmlspecialchars($disco['titulo']); ?></h1>
            
            <!-- Enlace al perfil del artista -->
            <a href="artista_detalle.php?id=<?php echo $disco['id_artista']; ?>" class="artist-link">
                <?php echo htmlspecialchars($disco['artista']); ?> &rarr;
            </a>

            <?php if (!empty($disco['genero'])): ?>
                <div class="genre-pill">
                    <?php echo htmlspecialchars($disco['genero']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($disco['descripcion'])): ?>
                <p class="album-description">
                    <?php echo htmlspecialchars($disco['descripcion']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lista de canciones del disco -->
    <div class="tracklist-section">
        <h2 class="tracklist-title">Lista de canciones</h2>
        <p class="tracklist-meta">
            <?php echo $total_canciones; ?> canciones · 
            Publicado el <?php echo htmlspecialchars($disco['fecha_formateada']); ?>
            <?php if (!empty($disco['sello'])): ?>
                - <?php echo htmlspecialchars($disco['sello']); ?>
            <?php endif; ?>
        </p>

        <div class="tracklist-table">
            <?php if ($res_canciones && $total_canciones > 0): ?>
                <?php while ($cancion = mysqli_fetch_assoc($res_canciones)): ?>
                    <div class="track-row">
                        <span class="track-number"><?php echo sprintf("%02d", $cancion['numero_pista']); ?></span>
                        <span class="track-name"><?php echo htmlspecialchars($cancion['titulo']); ?></span>
                        <span class="track-duration"><?php echo htmlspecialchars($cancion['duracion']); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-tracks">No hay canciones registradas para este disco.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
// 6. Cierre de conexión e inclusión de pie de página
mysqli_close($conexion);
include("includes/footer.php");
?>