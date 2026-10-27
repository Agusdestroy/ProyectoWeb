<?php
// 1. Conexión a la base de datos
require("bd/conexion_bd.php");

// 2. Variables para el formulario (vacías al entrar por primera vez)
// $errores guarda un error por campo: título y detalle
$nombre  = '';
$email   = '';
$errores = array();

// 3. Si se envió el formulario (POST), procesamos los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = isset($_POST['nombre'])     ? trim($_POST['nombre'])     : '';
    $email      = isset($_POST['email'])      ? trim($_POST['email'])      : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena']       : '';
    $terminos   = isset($_POST['terminos']);

    // 3.1 Validaciones de cada campo
    if ($nombre === '') {
        $errores['nombre'] = array('titulo' => 'Falta el nombre.', 'detalle' => 'Ingresa un nombre de usuario.');
    }

    if ($email === '') {
        $errores['email'] = array('titulo' => 'Falta el correo.', 'detalle' => 'Ingresa tu correo electrónico.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = array('titulo' => 'Correo inválido.', 'detalle' => 'Revisa que esté bien escrito, por ejemplo nicolas@correo.com.');
    }

    if (strlen($contrasena) < 8 || !preg_match('/[a-zA-Z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
        $errores['contrasena'] = array('titulo' => 'Contraseña incorrecta', 'detalle' => 'Usa al menos 8 caracteres, una letra y un número.');
    }

    if (!$terminos) {
        $errores['terminos'] = array('titulo' => 'Faltan los términos.', 'detalle' => 'Tienes que aceptar los términos de uso para crear tu cuenta.');
    }

    $nombre_clean = mysqli_real_escape_string($conexion, $nombre);
    $email_clean  = mysqli_real_escape_string($conexion, $email);

    // 3.2 Verificar que el nombre no esté registrado
    if (!isset($errores['nombre'])) {
        $res_nombre = mysqli_query($conexion, "SELECT id_usuario FROM usuarios WHERE nombre_usuario = '$nombre_clean'");

        if (!$res_nombre) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        if (mysqli_num_rows($res_nombre) > 0) {
            $errores['nombre'] = array('titulo' => 'El nombre ya existe.', 'detalle' => 'Elige otro nombre de usuario.');
        }
    }

    // 3.3 Verificar que el correo no esté registrado
    if (!isset($errores['email'])) {
        $res_email = mysqli_query($conexion, "SELECT id_usuario FROM usuarios WHERE email = '$email_clean'");

        if (!$res_email) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        if (mysqli_num_rows($res_email) > 0) {
            $errores['email'] = array('titulo' => 'El correo ya existe.', 'detalle' => 'Revisa tus datos para volver a intentar.');
        }
    }

    // 3.4 Si no hay errores, insertamos el usuario y redirigimos al login
    if (empty($errores)) {
        $contrasena_clean = mysqli_real_escape_string($conexion, $contrasena);

        $query_insert = "INSERT INTO usuarios (nombre_usuario, email, contrasena)
                         VALUES ('$nombre_clean', '$email_clean', '$contrasena_clean')";

        if (!mysqli_query($conexion, $query_insert)) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        mysqli_close($conexion);
        header("Location: login.php?registro=exitoso");
        exit;
    }
}

// 4. Cabecera (después de procesar, para poder redirigir antes)
include("includes/header.php");

// 5. Traer 4 portadas de discos para la decoración superior
$query_portadas = "SELECT titulo, portada_url FROM discos
                   WHERE portada_url IS NOT NULL
                   ORDER BY RAND() LIMIT 4";
$res_portadas = mysqli_query($conexion, $query_portadas);
?>

<section class="register-section">

    <!-- Portadas decorativas -->
    <div class="register-covers">
        <?php if ($res_portadas): ?>
            <?php while ($portada = mysqli_fetch_assoc($res_portadas)): ?>
                <img src="<?php echo htmlspecialchars($portada['portada_url']); ?>" alt="<?php echo htmlspecialchars($portada['titulo']); ?>">
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <h1 class="register-title">Tu colección empieza aquí.</h1>
    <p class="register-description">Crea una cuenta para guardar discos, compartir listas y encontrar a quienes escuchan como tú.</p>

    <!-- Formulario de Registro (POST) -->
    <!-- novalidate: desactiva los mensajes del navegador, los errores los muestra PHP -->
    <form method="POST" action="registro.php" class="register-form" novalidate>

        <!-- Campo Nombre -->
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" maxlength="50" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Nicolás" required>
        <?php if (isset($errores['nombre'])): ?>
            <div class="form-alert form-alert-error">
                <span class="alert-icon">!</span>
                <div>
                    <p class="alert-title"><?php echo $errores['nombre']['titulo']; ?></p>
                    <p class="alert-detail"><?php echo $errores['nombre']['detalle']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Campo Correo -->
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($email); ?>" placeholder="nicolas@correo.com" required>
        <?php if (isset($errores['email'])): ?>
            <div class="form-alert form-alert-error">
                <span class="alert-icon">!</span>
                <div>
                    <p class="alert-title"><?php echo $errores['email']['titulo']; ?></p>
                    <p class="alert-detail"><?php echo $errores['email']['detalle']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Campo Contraseña -->
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" minlength="8" required>
        <span class="form-hint">Usa al menos 8 caracteres, una letra y un número.</span>
        <?php if (isset($errores['contrasena'])): ?>
            <div class="form-alert form-alert-error">
                <span class="alert-icon">!</span>
                <div>
                    <p class="alert-title"><?php echo $errores['contrasena']['titulo']; ?></p>
                    <p class="alert-detail"><?php echo $errores['contrasena']['detalle']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Términos y condiciones -->
        <label class="form-checkbox">
            <input type="checkbox" name="terminos" required>
            Acepto los términos de uso y la política de privacidad de Surco.
        </label>
        <?php if (isset($errores['terminos'])): ?>
            <div class="form-alert form-alert-error">
                <span class="alert-icon">!</span>
                <div>
                    <p class="alert-title"><?php echo $errores['terminos']['titulo']; ?></p>
                    <p class="alert-detail"><?php echo $errores['terminos']['detalle']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn-submit">Crear mi cuenta</button>
    </form>

    <p class="register-login">¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>
