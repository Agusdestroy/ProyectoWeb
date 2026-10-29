<?php
session_start();
require("bd/conexion_bd.php");

// 1. Verificar sesión activa del usuario
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = (int)$_SESSION['id_usuario'];
$id_disco   = isset($_POST['id_disco']) ? (int)$_POST['id_disco'] : 0;

if ($id_disco > 0) {
    // 2. Verificar si el disco ya está registrado en favoritos
    $query_check = "SELECT id_disco FROM favoritos WHERE id_usuario = $id_usuario AND id_disco = $id_disco";
    $res_check   = mysqli_query($conexion, $query_check);

    if (mysqli_num_rows($res_check) > 0) {
        // Ya es favorito -> Eliminar registro
        $query_fav = "DELETE FROM favoritos WHERE id_usuario = $id_usuario AND id_disco = $id_disco";
    } else {
        // No es favorito -> Insertar nuevo registro
        $query_fav = "INSERT INTO favoritos (id_usuario, id_disco, fecha_agregado) VALUES ($id_usuario, $id_disco, NOW())";
    }

    mysqli_query($conexion, $query_fav);
}

mysqli_close($conexion);

// 3. Volver a la página desde la cual se hizo click (o discos.php por defecto)
$pagina_origen = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'discos.php';
header("Location: " . $pagina_origen);
exit();
?>