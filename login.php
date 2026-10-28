<?php
// 1. Iniciar la sesión (antes de cualquier HTML) y conectar a la base de datos
session_start();
require("bd/conexion_bd.php");

// 2. Variables para el formulario
$email = '';
$error = '';

// Si viene de registro.php con éxito, mostramos el mensaje verde
$registro_exitoso = isset($_GET['registro']) && $_GET['registro'] === 'exitoso';

// 3. Si se envió el formulario (POST), verificamos los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = isset($_POST['email'])      ? trim($_POST['email']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena']  : '';

    if ($email === '' || $contrasena === '') {
        $error = 'Completa tu correo y tu contraseña.';
    } else {
        // 3.1 Buscar un usuario con ese correo y esa contraseña
        $email_clean      = mysqli_real_escape_string($conexion, $email);
        $contrasena_clean = mysqli_real_escape_string($conexion, $contrasena);

        $query_usuario = "SELECT id_usuario, nombre_usuario FROM usuarios
                            WHERE email = '$email_clean' AND contrasena = '$contrasena_clean'";
        $res_usuario = mysqli_query($conexion, $query_usuario);

        if (!$res_usuario) {
            die("Error en la consulta SQL: " . mysqli_error($conexion));
        }

        // 3.2 Si existe, guardamos sus datos en la sesión y vamos al inicio
        if (mysqli_num_rows($res_usuario) === 1) {
            $usuario = mysqli_fetch_assoc($res_usuario);

            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['usuario']    = $usuario['nombre_usuario'];

            mysqli_close($conexion);
            header("Location: index.php");
            exit;
        }

        $error = 'El correo o la contraseña no son correctos.';
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

    <h1 class="register-title">Vuelve a tu lado del surco.</h1>
    <p class="register-description">Inicia sesión para guardar tus discos favoritos y seguir dando forma a tus listas.</p>

    <!-- Mensaje de registro exitoso -->
    <?php if ($registro_exitoso && $error === ''): ?>
        <div class="form-alert form-alert-success">
            <span class="alert-icon">!</span>
            <div>
                <p class="alert-title">Registro Exitoso!</p>
                <p class="alert-detail">Ya puedes iniciar sesión.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Mensaje de error -->
    <?php if ($error !== ''): ?>
        <div class="form-alert form-alert-error">
            <span class="alert-icon">!</span>
            <div>
                <p class="alert-title"><?php echo $error; ?></p>
                <p class="alert-detail">Revisa tus datos o recupera tu contraseña para volver a entrar.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Formulario de Login (POST) -->
    <!-- novalidate: desactiva los mensajes del navegador, los errores los muestra PHP -->
    <form method="POST" action="login.php" class="register-form" novalidate>

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="elena@correo.com">

        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" class="<?php echo ($error !== '') ? 'input-error' : ''; ?>">

        <!-- Recordarme (solo visual) y recuperar contraseña -->
        <div class="form-options">
            <label class="form-checkbox">
                <input type="checkbox" name="recordarme">
                Recordarme
            </label>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn-submit">
            <?php echo ($error !== '') ? 'Intentar de nuevo' : 'Iniciar sesión'; ?>
        </button>
    </form>

    <p class="register-login">¿Aún no tienes cuenta? <a href="registro.php">Crear una cuenta</a></p>
</section>

<?php
mysqli_close($conexion);
include("includes/footer.php");
?>
