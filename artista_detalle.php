<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("bd/conexion_bd.php");

$id_artista = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_artista <= 0) {
    header("Location: discos.php");
    exit();
}

$query_artista = "SELECT id_artista, nombre, IFNULL(biografia, '') AS biografia, IFNULL(imagen_url, 'Img/sin-portada.jpg') AS imagen_url 
                    FROM artistas 
                    WHERE id_artista = $id_artista";

$res_artista = mysqli_query($conexion, $query_artista);

if (!$res_artista) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$artista = mysqli_fetch_assoc($res_artista);

if (!$artista) {
    header("Location: discos.php");
    exit();
}

$query_discos = "SELECT id_disco, titulo, portada_url, descripcion, YEAR(fecha_lanzamiento) AS anio 
                    FROM discos 
                    WHERE id_artista = $id_artista 
                    ORDER BY fecha_lanzamiento ASC";

$res_discos = mysqli_query($conexion, $query_discos);
$total_discos = $res_discos ? mysqli_num_rows($res_discos) : 0;

$lista_discos = array();
if ($res_discos && $total_discos > 0) {
    while ($row = mysqli_fetch_assoc($res_discos)) {
        $lista_discos[] = $row;
    }
}

// Recién aquí incluimos la cabecera
include("includes/header.php");
?>

<section class="artist-detail-container">
    <nav class="breadcrumb">
        <a href="discos.php">Discos</a> / 
        <span>Artistas</span> / 
        <span class="breadcrumb-active"><?php echo htmlspecialchars($artista['nombre']); ?></span>
    </nav>

    <div class="artist-header">
        <div class="artist-cover-big">
            <img src="<?php echo htmlspecialchars($artista['imagen_url']); ?>" alt="<?php echo htmlspecialchars($artista['nombre']); ?>">
        </div>

        <div class="artist-info-content">
            <span class="artist-tag">ARTISTA</span>
            <h1 class="artist-main-title"><?php echo htmlspecialchars($artista['nombre']); ?></h1>

            <?php if (!empty($artista['biografia'])): ?>
                <p class="artist-bio"><?php echo htmlspecialchars($artista['biografia']); ?></p>
            <?php endif; ?>

            <div class="artist-meta-pill">
                <?php echo $total_discos; ?> <?php echo ($total_discos == 1) ? 'álbum registrado' : 'álbumes registrados'; ?> en Surco
            </div>
        </div>
    </div>

    <div class="discography-section">
        <h2 class="section-subtitle">Su discografía</h2>
        <p class="section-subtext">
            <?php echo $total_discos; ?> <?php echo ($total_discos == 1) ? 'disco para explorar en el catálogo.' : 'discos para recorrer su trayectoria sonora.'; ?>
        </p>

        <div class="albums-grid">
            <?php if ($total_discos > 0): ?>
                <?php foreach ($lista_discos as $disco): ?>
                    <a href="disco_detalle.php?id=<?php echo $disco['id_disco']; ?>" class="album-card-link">
                        <div class="album-card">
                            <div class="cover-wrapper">
                                <img src="<?php echo htmlspecialchars($disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($disco['titulo']); ?>">
                            </div>
                            <h3 class="album-title"><?php echo htmlspecialchars($disco['titulo']); ?></h3>
                            <p class="album-artist">
                                <?php echo htmlspecialchars($artista['nombre']); ?> · <?php echo htmlspecialchars($disco['anio']); ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-tracks">No hay discos registrados para este artista aún.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($total_discos > 0): ?>
        <div class="featured-recommendations">
            <div class="recommendation-card">
                <span class="rec-tag">POR DÓNDE EMPEZAR</span>
                <div class="rec-content">
                    <img src="<?php echo htmlspecialchars($lista_discos[0]['portada_url']); ?>" alt="<?php echo htmlspecialchars($lista_discos[0]['titulo']); ?>">
                    <div class="rec-text">
                        <h3><?php echo htmlspecialchars($lista_discos[0]['titulo']); ?></h3>
                        <p>
                            <?php 
                                echo !empty($lista_discos[0]['descripcion']) 
                                    ? htmlspecialchars($lista_discos[0]['descripcion']) 
                                    : 'Un trabajo clave dentro de la discografía del artista.'; 
                            ?>
                        </p>
                        <a href="disco_detalle.php?id=<?php echo $lista_discos[0]['id_disco']; ?>" class="rec-link">Ver disco &rarr;</a>
                    </div>
                </div>
            </div>

            <?php if ($total_discos > 1): 
                $ultimo_disco = $lista_discos[$total_discos - 1];
            ?>
                <div class="recommendation-card">
                    <span class="rec-tag">PARA SEGUIR EL VIAJE</span>
                    <div class="rec-content">
                        <img src="<?php echo htmlspecialchars($ultimo_disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($ultimo_disco['titulo']); ?>">
                        <div class="rec-text">
                            <h3><?php echo htmlspecialchars($ultimo_disco['titulo']); ?></h3>
                            <p>
                                <?php 
                                    echo !empty($ultimo_disco['descripcion']) 
                                        ? htmlspecialchars($ultimo_disco['descripcion']) 
                                        : 'Un álbum para explorar la evolución de su propuesta musical.'; 
                                ?>
                            </p>
                            <a href="disco_detalle.php?id=<?php echo $ultimo_disco['id_disco']; ?>" class="rec-link">Ver disco &rarr;</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>