<?php
// 1. Iniciar la sesión y verificar que el usuario esté logueado
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

require("bd/conexion_bd.php");

$id_usuario = (int)$_SESSION['id_usuario'];
$errores    = array();

// Nombres de los meses para mostrar "En Surco desde marzo de 2025"
$meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
               'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

// 2. Datos actuales del usuario (para cargar el formulario y la columna derecha)
$query_usuario = "SELECT nombre_usuario, email, biografia, MONTH(fecha_registro) AS mes, YEAR(fecha_registro) AS anio
                    FROM usuarios
                    WHERE id_usuario = $id_usuario";
$res_usuario = mysqli_query($conexion, $query_usuario);

if (!$res_usuario) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$usuario   = mysqli_fetch_assoc($res_usuario);
$nombre    = $usuario['nombre_usuario'];
$biografia = $usuario['biografia'] !== null ? $usuario['biografia'] : '';

// 3. Si se envió el formulario (POST), procesamos los cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = isset($_POST['nombre'])    ? trim($_POST['nombre'])    : '';
    $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : '';

    // 3.1 Validaciones de cada campo
    if ($nombre === '') {
        $errores['nombre'] = array('titulo' => 'Falta el nombre.', 'detalle' => 'Ingresa un nombre de usuario.');
    }

    // mb_strlen cuenta bien las letras con tilde (strlen las cuenta doble)
    if (mb_strlen($biografia) > 240) {
        $errores['biografia'] = array('titulo' => 'Biografía muy larga.', 'detalle' => 'Usa como máximo 240 caracteres.');
    }

    $nombre_clean    = mysqli_real_escape_string($conexion, $nombre);
    $biografia_clean = mysqli_real_escape_string($conexion, $biografia);

    // 3.2 Verificar que el nombre no lo use OTRO usuario
    if (!isset($errores['nombre'])) {
        $query_existe = "SELECT id_usuario FROM usuarios
                         WHERE nombre_usuario = '$nombre_clean' AND id_usuario <> $id_usuario";
        $res_existe = mysqli_query($conexion, $query_existe);

        if (!$res_existe) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        if (mysqli_num_rows($res_existe) > 0) {
            $errores['nombre'] = array('titulo' => 'El nombre ya existe.', 'detalle' => 'Elige otro nombre de usuario.');
        }
    }

    // 3.3 Si no hay errores, guardamos los cambios y volvemos al perfil
    if (empty($errores)) {
        $query_update = "UPDATE usuarios
                         SET nombre_usuario = '$nombre_clean', biografia = '$biografia_clean'
                         WHERE id_usuario = $id_usuario";

        if (!mysqli_query($conexion, $query_update)) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        // Actualizamos el nombre guardado en la sesión (lo usa el header)
        $_SESSION['usuario'] = $nombre;

        mysqli_close($conexion);
        header("Location: perfil.php?guardado=1");
        exit;
    }
}

// 4. Cabecera (después de procesar, para poder redirigir antes)
include("includes/header.php");

$inicial = strtoupper(substr($usuario['nombre_usuario'], 0, 1));
?>

<section class="edit-profile-container">
    <span class="section-tag">TU CUENTA</span>
    <h1 class="section-title">Editar perfil</h1>
    <p class="section-description">Haz que tu espacio en Surco se sienta un poco más tuyo.</p>

    <div class="edit-profile-layout">

        <!-- Columna izquierda: formulario -->
        <form method="POST" action="editar_perfil.php" class="register-form" novalidate>

            <!-- Inicial del usuario -->
            <div class="edit-avatar-box">
                <div class="profile-avatar profile-avatar-small"><?php echo htmlspecialchars($inicial); ?></div>
                <p>Tu inicial se muestra como foto de perfil.</p>
            </div>

            <!-- Campo Nombre de usuario -->
            <label for="nombre">Nombre de usuario</label>
            <input type="text" id="nombre" name="nombre" maxlength="50" value="<?php echo htmlspecialchars($nombre); ?>">
            <span class="form-hint">Así te verá la comunidad.</span>
            <?php if (isset($errores['nombre'])): ?>
                <div class="form-alert form-alert-error">
                    <span class="alert-icon">!</span>
                    <div>
                        <p class="alert-title"><?php echo $errores['nombre']['titulo']; ?></p>
                        <p class="alert-detail"><?php echo $errores['nombre']['detalle']; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Campo Biografía -->
            <label for="biografia">Biografía</label>
            <textarea id="biografia" name="biografia" rows="3" maxlength="240"><?php echo htmlspecialchars($biografia); ?></textarea>
            <span class="form-hint">Máximo 240 caracteres.</span>
            <?php if (isset($errores['biografia'])): ?>
                <div class="form-alert form-alert-error">
                    <span class="alert-icon">!</span>
                    <div>
                        <p class="alert-title"><?php echo $errores['biografia']['titulo']; ?></p>
                        <p class="alert-detail"><?php echo $errores['biografia']['detalle']; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Botones -->
            <div class="edit-actions">
                <a href="perfil.php" class="btn-dark">Cancelar</a>
                <button type="submit" class="btn-submit">✓ Guardar cambios</button>
            </div>
        </form>

        <!-- Columna derecha: información -->
        <aside class="edit-sidebar">
            <div class="sidebar-card">
                <h3>Lo que compartes</h3>
                <p>Tu nombre, biografía y discos favoritos ayudan a otras personas a conocer tu forma de escuchar.</p>
                <p>Tu correo nunca se muestra en el perfil.</p>
            </div>

            <div class="sidebar-card">
                <h3>Tu cuenta</h3>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                <p>En Surco desde <?php echo $meses[$usuario['mes']] . ' de ' . $usuario['anio']; ?></p>
            </div>
        </aside>
    </div>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>
