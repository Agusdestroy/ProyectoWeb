<?php
// 1. Iniciar la sesión y verificar que el usuario esté logueado
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

require("bd/conexion_bd.php");
include("includes/header.php");

$id_usuario = (int)$_SESSION['id_usuario'];

// Si viene de editar_perfil.php con éxito, mostramos el mensaje verde
$perfil_guardado = isset($_GET['guardado']);

// Nombres de los meses para mostrar "En Surco desde marzo de 2025"
$meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
               'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

// 2. Datos del usuario
$query_usuario = "SELECT nombre_usuario, biografia, MONTH(fecha_registro) AS mes, YEAR(fecha_registro) AS anio
                    FROM usuarios
                    WHERE id_usuario = $id_usuario";
$res_usuario = mysqli_query($conexion, $query_usuario);

if (!$res_usuario) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$usuario = mysqli_fetch_assoc($res_usuario);
$inicial = strtoupper(substr($usuario['nombre_usuario'], 0, 1));

// 3. Cantidad de favoritos y de listas
$res_total_fav   = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM favoritos WHERE id_usuario = $id_usuario");
$fila_fav        = mysqli_fetch_assoc($res_total_fav);
$total_favoritos = $fila_fav['total'];

$res_total_listas = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM listas WHERE id_usuario = $id_usuario");
$fila_listas      = mysqli_fetch_assoc($res_total_listas);
$total_listas     = $fila_listas['total'];

// 4. Los 4 favoritos más recientes
$query_favoritos = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio,
                           a.nombre AS artista
                    FROM favoritos f
                    JOIN discos d ON f.id_disco = d.id_disco
                    JOIN artistas a ON d.id_artista = a.id_artista
                    WHERE f.id_usuario = $id_usuario
                    ORDER BY f.fecha_agregado DESC
                    LIMIT 4";
$res_favoritos = mysqli_query($conexion, $query_favoritos);

if (!$res_favoritos) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

// 5. Las 3 listas más recientes, con la cantidad de discos de cada una
$query_listas = "SELECT l.id_lista, l.titulo, l.descripcion, COUNT(ld.id_disco) AS total_discos
                 FROM listas l
                 LEFT JOIN lista_discos ld ON l.id_lista = ld.id_lista
                 WHERE l.id_usuario = $id_usuario
                 GROUP BY l.id_lista
                 ORDER BY l.fecha_creacion DESC
                 LIMIT 3";
$res_listas = mysqli_query($conexion, $query_listas);

if (!$res_listas) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}
?>

<section class="profile-container">

    <!-- Migas de pan -->
    <div class="breadcrumb">
        Mi cuenta / <span class="breadcrumb-active">Perfil</span>
    </div>

    <!-- Mensaje de perfil guardado -->
    <?php if ($perfil_guardado): ?>
        <div class="form-alert form-alert-success">
            <span class="alert-icon">!</span>
            <div>
                <p class="alert-title">Cambios guardados.</p>
                <p class="alert-detail">Tu perfil ya está actualizado.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Encabezado del perfil -->
    <div class="profile-header">
        <div class="profile-avatar"><?php echo htmlspecialchars($inicial); ?></div>

        <div class="profile-info">
            <span class="section-tag">TU PERFIL</span>
            <h1 class="profile-name"><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></h1>
            <p class="profile-meta">
                @<?php echo htmlspecialchars($usuario['nombre_usuario']); ?> ·
                En Surco desde <?php echo $meses[$usuario['mes']] . ' de ' . $usuario['anio']; ?>
            </p>
            <?php if (!empty($usuario['biografia'])): ?>
                <p class="profile-bio"><?php echo htmlspecialchars($usuario['biografia']); ?></p>
            <?php endif; ?>
        </div>

        <a href="editar_perfil.php" class="btn-dark">✎ Editar perfil</a>
    </div>

    <!-- Contadores -->
    <div class="profile-stats">
        <p><strong><?php echo $total_favoritos; ?></strong> favoritos</p>
        <p><strong><?php echo $total_listas; ?></strong> listas</p>
    </div>

    <!-- Favoritos destacados -->
    <div class="profile-block">
        <div class="profile-block-header">
            <div>
                <h2 class="section-subtitle">Mis favoritos destacados</h2>
                <p class="section-subtext">Los últimos discos que agregaste. Una selección de tus <?php echo $total_favoritos; ?> favoritos.</p>
            </div>
            <a href="coleccion.php" class="rec-link">Ver los <?php echo $total_favoritos; ?> favoritos →</a>
        </div>

        <?php if (mysqli_num_rows($res_favoritos) > 0): ?>
            <div class="profile-favorites">
                <?php while ($disco = mysqli_fetch_assoc($res_favoritos)): ?>
                    <a href="disco_detalle.php?id=<?php echo $disco['id_disco']; ?>" class="album-card-link">
                        <div class="album-card">
                            <div class="cover-wrapper">
                                <img src="<?php echo htmlspecialchars($disco['portada_url']); ?>" alt="<?php echo htmlspecialchars($disco['titulo']); ?>">
                            </div>
                            <p class="album-title"><?php echo htmlspecialchars($disco['titulo']); ?></p>
                            <p class="album-artist"><?php echo htmlspecialchars($disco['artista']) . ' · ' . $disco['anio']; ?></p>

                            <!-- Estrellas: solo maqueta, todavía sin funcionalidad -->
                            <p class="album-rating">★★★★ <span>4,5</span></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="profile-empty">Todavía no tienes favoritos. <a href="discos.php">Explora discos</a> y guarda los que más te gusten.</p>
        <?php endif; ?>
    </div>

    <!-- Listas -->
    <div class="profile-block">
        <div class="profile-block-header">
            <div>
                <h2 class="section-subtitle">Mis listas</h2>
                <p class="section-subtext">Las listas que armaste en Surco.</p>
            </div>
            <a href="listas.php" class="rec-link">Gestionar listas →</a>
        </div>

        <?php if (mysqli_num_rows($res_listas) > 0): ?>
            <div class="profile-lists">
                <?php while ($lista = mysqli_fetch_assoc($res_listas)): ?>
                    <?php
                    // Traer hasta 3 portadas de los discos de esta lista
                    $id_lista = $lista['id_lista'];
                    $query_portadas = "SELECT d.titulo, d.portada_url
                                       FROM lista_discos ld
                                       JOIN discos d ON ld.id_disco = d.id_disco
                                       WHERE ld.id_lista = $id_lista
                                       LIMIT 3";
                    $res_portadas = mysqli_query($conexion, $query_portadas);
                    ?>
                    <div class="list-card">
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
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="profile-empty">Todavía no armaste ninguna lista. <a href="listas.php">Crea tu primera lista</a>.</p>
        <?php endif; ?>
    </div>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>
