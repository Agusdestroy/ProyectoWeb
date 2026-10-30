<?php
$servidor = "localhost";
$usuario = "root";       
$contrasena = "";        
$base_datos = "proyectoweb";   

//  Usamos la función básica para conectar
$conexion = mysqli_connect($servidor, $usuario, $contrasena, $base_datos);

// Verificamos si falló con un IF simple
if (!$conexion) {
    die("Error al conectar: " . mysqli_connect_error());
}
?>