<?php
session_start();
require("bd/conexion_bd.php");

$id_lista = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_lista <= 0) {
    header("Location: mi_coleccion.php?vista=listas");
    exit();
}

$query_lista = "SELECT l.titulo, l.descripcion, l.fecha_creacion, u.nombre_usuario, u.id_usuario
                FROM listas l
                JOIN usuarios u ON l.id_usuario = u.id_usuario
                WHERE l.id_lista = $id_lista";
$res_lista = mysqli_query($conexion, $query_lista);

if (!$res_lista || mysqli_num_rows($res_lista) === 0) {
    die("La lista no existe.");
}

$lista = mysqli_fetch_assoc($res_lista);

$query_discos = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio,
                        IFNULL(a.nombre, 'Artista Desconocido') AS artista
                 FROM lista_discos ld
                 JOIN discos d ON ld.id_disco = d.id_disco
                 LEFT JOIN artistas a ON d.id_artista = a.id_artista
                 WHERE ld.id_lista = $id_lista
                 ORDER BY ld.fecha_agregado ASC";
$res_discos = mysqli_query($conexion, $query_discos);
$total_discos = mysqli_num_rows($res_discos);

include("includes/header.php");
?>

<section class="explore-section">
    <div class="breadcrumb">
        <a href="mi_coleccion.php?vista=listas">Mis listas</a> / 
        <span class="breadcrumb-active"><?php echo htmlspecialchars($lista['titulo']); ?></span>
    </div>

    <div style="display: flex; gap: 30px; align-items: center; margin-bottom: 40px;">
        <h1 class="section-title" style="font-size: 2.2rem; margin: 0;"><?php echo htmlspecialchars($lista['titulo']); ?></h1>
    </div>

    <div class="list-detail-layout">
        <div class="list-detail-main">
            <p style="color: #9ca3af; margin-bottom: 20px; font-size: 0.9rem;">
                Una escucha para dejar que la noche se meza. (<?php echo $total_discos; ?> discos)
            </p>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php 
                $contador = 1;
                while ($disco = mysqli_fetch_assoc($res_discos)): 
                ?>
                    <div class="list-item-row" style="background-color: #181b20; border: 1px solid #262931; border-radius: 8px; padding: 10px 16px;">
                        <span style="color: #6b7280; font-weight: 700; font-size: 0.9rem; min-width: 24px;">
                            <?php echo str_pad($contador++, 2, "0", STR_PAD_LEFT); ?>
                        </span>
                        
                        <img src="<?php echo htmlspecialchars($disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($disco['titulo']); ?>" class="list-item-thumb">
                        
                        <div style="flex: 1;">
                            <a href="disco_detalle.php?id=<?php echo $disco['id_disco']; ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($disco['titulo']); ?>
                            </a>
                            <p style="color: #9ca3af; font-size: 0.8rem; margin-top: 2px;">
                                <?php echo htmlspecialchars($disco['artista']); ?> · <?php echo $disco['anio']; ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <aside class="list-detail-sidebar">
            <div class="sidebar-card">
                <p style="font-weight: 600; font-size: 0.95rem; margin-bottom: 6px;">
                    <?php echo htmlspecialchars($lista['nombre_usuario']); ?>
                </p>
                <p style="color: #9ca3af; font-size: 0.8rem; margin-bottom: 14px;">
                    Esta lista es tuya.
                </p>
                <?php if (!empty($lista['descripcion'])): ?>
                    <p style="color: #d1d5db; font-size: 0.85rem; line-height: 1.4; margin-bottom: 16px;">
                        <?php echo htmlspecialchars($lista['descripcion']); ?>
                    </p>
                <?php endif; ?>
                <a href="perfil.php" style="color: #22c55e; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                    Ver mi perfil →
                </a>
            </div>
        </aside>

    </div>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>