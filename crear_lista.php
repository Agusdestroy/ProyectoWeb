<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require("bd/conexion_bd.php");

$id_usuario = (int)$_SESSION['id_usuario'];
$errores    = array();

// Inicializar la lista temporal de discos en la sesión
if (!isset($_SESSION['discos_nueva_lista'])) {
    $_SESSION['discos_nueva_lista'] = array();
}

// A. Agregar un disco a la lista temporal
if (isset($_POST['agregar_disco'])) {
    $id_disco_add = (int)$_POST['id_disco_add'];
    if ($id_disco_add > 0 && !in_array($id_disco_add, $_SESSION['discos_nueva_lista'])) {
        $_SESSION['discos_nueva_lista'][] = $id_disco_add;
    }
    header("Location: crear_lista.php");
    exit();
}

// B. Quitar un disco de la lista temporal
if (isset($_GET['quitar_disco'])) {
    $id_disco_del = (int)$_GET['quitar_disco'];
    $key = array_search($id_disco_del, $_SESSION['discos_nueva_lista']);
    if ($key !== false) {
        unset($_SESSION['discos_nueva_lista'][$key]);
        $_SESSION['discos_nueva_lista'] = array_values($_SESSION['discos_nueva_lista']); // Reindexar array
    }
    header("Location: crear_lista.php");
    exit();
}

// C. Guardar la lista definitiva en la BD
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

        // 1. Insertar en la tabla 'listas'
        $query_lista = "INSERT INTO listas (id_usuario, titulo, descripcion, fecha_creacion) 
                        VALUES ($id_usuario, '$titulo_clean', '$descripcion_clean', NOW())";

        if (mysqli_query($conexion, $query_lista)) {
            $id_lista_nueva = mysqli_insert_id($conexion);

            // 2. Insertar los discos en la tabla intermedia 'lista_discos'
            foreach ($_SESSION['discos_nueva_lista'] as $id_disco) {
                $query_item = "INSERT INTO lista_discos (id_lista, id_disco, fecha_agregado) 
                               VALUES ($id_lista_nueva, $id_disco, NOW())";
                mysqli_query($conexion, $query_item);
            }

            // Limpiar sesión temporal y redirigir
            unset($_SESSION['discos_nueva_lista']);
            mysqli_close($conexion);
            header("Location: mi_coleccion.php?vista=listas");
            exit();
        } else {
            die("Error al crear la lista: " . mysqli_error($conexion));
        }
    }
}

// Búsqueda de discos para agregar
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

// Cargar información de los discos seleccionados actualmente
$discos_seleccionados = array();
if (!empty($_SESSION['discos_nueva_lista'])) {
    $ids_string = implode(',', $_SESSION['discos_nueva_lista']);
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

<section class="explore-section" style="max-width: 680px; margin: 0 auto;">
    
    <!-- Enlace superior -->
    <div style="margin-bottom: 20px;">
        <a href="mi_coleccion.php?vista=listas" style="color: #9ca3af; text-decoration: none; font-size: 0.85rem;">← Mis listas</a>
    </div>

    <h1 class="section-title">Crear una lista</h1>
    <p class="section-description" style="margin-bottom: 30px;">
        Reúne discos que tengan algo en común. O solo algo tuyo.
    </p>

    <!-- Errores generales -->
    <?php if (!empty($errores)): ?>
        <div class="form-alert form-alert-error" style="margin-bottom: 20px;">
            <p class="alert-title">Revisa los campos:</p>
            <?php foreach ($errores as $err): ?>
                <p class="alert-detail">• <?php echo $err; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario de Búsqueda para añadir discos -->
    <div style="background-color: #181b20; border: 1px solid #262931; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
        <label style="color: #ffffff; font-weight: 600; display: block; margin-bottom: 8px;">Añade discos a tu lista</label>
        
        <form method="GET" action="crear_lista.php" style="display: flex; gap: 10px;">
            <div class="filter-search-box" style="flex: 1;">
                <span class="search-icon">🔍</span>
                <input type="text" name="buscar_disco" value="<?php echo htmlspecialchars($buscar_disco); ?>" placeholder="Buscar por disco o artista...">
            </div>
            <button type="submit" class="btn-secondary">Buscar</button>
        </form>

        <!-- Resultados de búsqueda -->
        <?php if ($res_busqueda && mysqli_num_rows($res_busqueda) > 0): ?>
            <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                <?php while ($disco_b = mysqli_fetch_assoc($res_busqueda)): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; background: #111317; padding: 8px 12px; border-radius: 6px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="<?php echo htmlspecialchars($disco_b['portada_url']); ?>" style="width: 36px; height: 36px; border-radius: 4px; object-fit: cover;">
                            <div>
                                <p style="color: #ffffff; font-size: 0.85rem; font-weight: 600; margin: 0;"><?php echo htmlspecialchars($disco_b['titulo']); ?></p>
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

    <!-- Lista de discos agregados -->
    <div style="margin-bottom: 30px;">
        <p style="color: #ffffff; font-weight: 600; font-size: 0.9rem; margin-bottom: 12px;">
            <?php echo count($discos_seleccionados); ?> discos añadidos
        </p>

        <?php if (!empty($discos_seleccionados)): ?>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <?php foreach ($discos_seleccionados as $item): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; background-color: #181b20; border: 1px solid #262931; border-radius: 6px; padding: 10px 14px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="<?php echo htmlspecialchars($item['portada_url']); ?>" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                            <div>
                                <p style="color: #ffffff; font-weight: 600; font-size: 0.88rem; margin: 0;"><?php echo htmlspecialchars($item['titulo']); ?></p>
                                <p style="color: #9ca3af; font-size: 0.78rem; margin: 0;"><?php echo htmlspecialchars($item['artista']); ?> · <?php echo $item['anio']; ?></p>
                            </div>
                        </div>
                        <a href="crear_lista.php?quitar_disco=<?php echo $item['id_disco']; ?>" style="color: #ef4444; text-decoration: none; font-size: 1.1rem;" title="Quitar de la lista">✕</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #6b7280; font-size: 0.85rem; background: #181b20; padding: 15px; border-radius: 6px; border: 1px dashed #262931;">
                Aún no has añadido discos. Usa el buscador de arriba para agregar canciones/discos a tu lista.
            </p>
        <?php endif; ?>
    </div>

    <!-- Formulario Principal: Nombre y Descripción -->
    <form method="POST" action="crear_lista.php">
        <div style="margin-bottom: 20px;">
            <label for="titulo" style="color: #ffffff; display: block; margin-bottom: 6px; font-weight: 600;">Nombre de la lista</label>
            <input type="text" id="titulo" name="titulo" class="filter-search-box" style="width: 100%; box-sizing: border-box; background: #181b20; border: 1px solid #262931; color: #fff; padding: 10px; border-radius: 6px; outline: none;" placeholder="Ej: Noches sin prisa">
        </div>

        <div style="margin-bottom: 30px;">
            <label for="descripcion" style="color: #ffffff; display: block; margin-bottom: 6px; font-weight: 600;">Descripción · opcional</label>
            <textarea id="descripcion" name="descripcion" rows="3" class="filter-search-box" style="width: 100%; box-sizing: border-box; background: #181b20; border: 1px solid #262931; color: #fff; padding: 10px; border-radius: 6px; outline: none;" placeholder="Para cuando la ciudad baja el volumen..."></textarea>
        </div>

        <!-- Botones de Acción -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #1f222a; padding-top: 20px;">
            <a href="mi_coleccion.php?vista=listas" class="btn-secondary" style="text-decoration: none;">Cancelar</a>
            <button type="submit" name="guardar_lista" class="btn-primary">✓ Crear lista</button>
        </div>
    </form>

</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>