<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require("bd/conexion_bd.php");

$id_usuario = (int)$_SESSION['id_usuario'];
$errores    = array();

if (!isset($_SESSION['discos_nueva_lista'])) {
    $_SESSION['discos_nueva_lista'] = array();
}

if (isset($_POST['agregar_disco'])) {
    $id_disco_add = (int)$_POST['id_disco_add'];
    if ($id_disco_add > 0 && !in_array($id_disco_add, $_SESSION['discos_nueva_lista'])) {
        $_SESSION['discos_nueva_lista'][] = $id_disco_add;
    }
    header("Location: crear_lista.php");
    exit();
}

if (isset($_GET['quitar_disco'])) {
    $id_disco_del = (int)$_GET['quitar_disco'];
    $key = array_search($id_disco_del, $_SESSION['discos_nueva_lista']);
    if ($key !== false) {
        unset($_SESSION['discos_nueva_lista'][$key]);
        $_SESSION['discos_nueva_lista'] = array_values($_SESSION['discos_nueva_lista']);
    }
    header("Location: crear_lista.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_lista'])) {
    $titulo      = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    if ($titulo === '') {
        $errores['titulo'] = 'Ingresa un nombre para tu lista.';
    }

    if (empty($_SESSION['discos_nueva_lista'])) {
        $errores['discos'] = 'Agrega al menos un disco a tu lista.';
    }

    if (empty($errores)) {
        $titulo_clean      = mysqli_real_escape_string($conexion, $titulo);
        $descripcion_clean = mysqli_real_escape_string($conexion, $descripcion);

        $query_lista = "INSERT INTO listas (id_usuario, titulo, descripcion, fecha_creacion) 
                        VALUES ($id_usuario, '$titulo_clean', '$descripcion_clean', NOW())";

        if (mysqli_query($conexion, $query_lista)) {
            $id_lista_nueva = mysqli_insert_id($conexion);

            foreach ($_SESSION['discos_nueva_lista'] as $id_disco) {
                $query_item = "INSERT INTO lista_discos (id_lista, id_disco, fecha_agregado) 
                               VALUES ($id_lista_nueva, $id_disco, NOW())";
                mysqli_query($conexion, $query_item);
            }

            unset($_SESSION['discos_nueva_lista']);
            mysqli_close($conexion);
            header("Location: mi_coleccion.php?vista=listas");
            exit();
        } else {
            die("Error al crear la lista: " . mysqli_error($conexion));
        }
    }
}

$buscar_disco = isset($_GET['buscar_disco']) ? trim($_GET['buscar_disco']) : '';
$res_busqueda = null;

if (!empty($buscar_disco)) {
    $buscar_clean = mysqli_real_escape_string($conexion, $buscar_disco);
    $query_search = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio, a.nombre AS artista
                     FROM discos d
                     LEFT JOIN artistas a ON d.id_artista = a.id_artista
                     WHERE d.titulo LIKE '%$buscar_clean%' OR a.nombre LIKE '%$buscar_clean%'
                     LIMIT 4";
    $res_busqueda = mysqli_query($conexion, $query_search);
}

$discos_seleccionados = array();
if (!empty($_SESSION['discos_nueva_lista'])) {
    $ids_string = implode(',', array_map('intval', $_SESSION['discos_nueva_lista']));
    $query_sel = "SELECT d.id_disco, d.titulo, d.portada_url, YEAR(d.fecha_lanzamiento) AS anio, a.nombre AS artista
                  FROM discos d
                  LEFT JOIN artistas a ON d.id_artista = a.id_artista
                  WHERE d.id_disco IN ($ids_string)";
    $res_sel = mysqli_query($conexion, $query_sel);
    while ($row = mysqli_fetch_assoc($res_sel)) {
        $discos_seleccionados[] = $row;
    }
}

include("includes/header.php");
?>

<section class="explore-section list-container-narrow">
    
    <div style="margin-bottom: 20px;">
        <a href="mi_coleccion.php?vista=listas" class="breadcrumb-link">← Mis listas</a>
    </div>

    <h1 class="section-title">Crear una lista</h1>
    <p class="section-description">
        Reúne discos que tengan algo en común. O solo algo tuyo.
    </p>

    <?php if (!empty($errores)): ?>
        <div class="form-alert form-alert-error">
            <div>
                <p class="alert-title">Revisa los campos:</p>
                <?php foreach ($errores as $err): ?>
                    <p class="alert-detail">• <?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="list-panel">
        <label class="list-panel-title">Añade discos a tu lista</label>
        
        <form method="GET" action="crear_lista.php" style="display: flex; gap: 10px;">
            <div class="filter-search-box" style="flex: 1; margin-bottom: 0;">
                <span class="search-icon">🔍</span>
                <input type="text" name="buscar_disco" value="<?php echo htmlspecialchars($buscar_disco); ?>" placeholder="Buscar por disco o artista...">
            </div>
            <button type="submit" class="btn-secondary">Buscar</button>
        </form>

        <?php if ($res_busqueda && mysqli_num_rows($res_busqueda) > 0): ?>
            <div style="margin-top: 15px;">
                <?php while ($disco_b = mysqli_fetch_assoc($res_busqueda)): ?>
                    <div class="list-item-row">
                        <div class="list-item-info">
                            <img src="<?php echo htmlspecialchars($disco_b['portada_url']); ?>" class="list-item-thumb">
                            <div>
                                <p style="font-size: 0.85rem; font-weight: 600; margin: 0;"><?php echo htmlspecialchars($disco_b['titulo']); ?></p>
                                <p style="color: #9ca3af; font-size: 0.75rem; margin: 0;"><?php echo htmlspecialchars($disco_b['artista']); ?> · <?php echo $disco_b['anio']; ?></p>
                            </div>
                        </div>

                        <form method="POST" action="crear_lista.php">
                            <input type="hidden" name="id_disco_add" value="<?php echo $disco_b['id_disco']; ?>">
                            <button type="submit" name="agregar_disco" class="btn-primary" style="padding: 4px 12px; font-size: 0.8rem;">+ Añadir</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-bottom: 30px;">
        <p style="font-weight: 600; font-size: 0.9rem; margin-bottom: 12px;">
            <?php echo count($discos_seleccionados); ?> discos añadidos
        </p>

        <?php if (!empty($discos_seleccionados)): ?>
            <div>
                <?php foreach ($discos_seleccionados as $item): ?>
                    <div class="list-item-row" style="background-color: #181b20; border: 1px solid #262931; padding: 10px 14px;">
                        <div class="list-item-info">
                            <img src="<?php echo htmlspecialchars($item['portada_url']); ?>" class="list-item-thumb">
                            <div>
                                <p style="font-weight: 600; font-size: 0.88rem; margin: 0;"><?php echo htmlspecialchars($item['titulo']); ?></p>
                                <p style="color: #9ca3af; font-size: 0.78rem; margin: 0;"><?php echo htmlspecialchars($item['artista']); ?> · <?php echo $item['anio']; ?></p>
                            </div>
                        </div>
                        <a href="crear_lista.php?quitar_disco=<?php echo $item['id_disco']; ?>" style="color: #ef4444; text-decoration: none;" title="Quitar de la lista">✕</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.85rem; background: #181b20; padding: 15px; border-radius: 6px; border: 1px dashed #262931;">
                Aún no has añadido discos. Usa el buscador de arriba para agregar canciones/discos a tu lista.
            </p>
        <?php endif; ?>
    </div>

    <form method="POST" action="crear_lista.php">
        <div style="margin-bottom: 20px;">
            <label for="titulo" style="display: block; margin-bottom: 6px; font-weight: 600;">Nombre de la lista</label>
            <input type="text" id="titulo" name="titulo" placeholder="Ej: Noches sin prisa">
        </div>

        <div style="margin-bottom: 30px;">
            <label for="descripcion" style="display: block; margin-bottom: 6px; font-weight: 600;">Descripción · opcional</label>
            <textarea id="descripcion" name="descripcion" rows="3" placeholder="Para cuando la ciudad baja el volumen..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #1f222a; padding-top: 20px;">
            <a href="mi_coleccion.php?vista=listas" class="btn-secondary">Cancelar</a>
            <button type="submit" name="guardar_lista" class="btn-primary">✓ Crear lista</button>
        </div>
    </form>

</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>