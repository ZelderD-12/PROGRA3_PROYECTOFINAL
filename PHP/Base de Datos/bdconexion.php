<?php
    $server="212.1.211.51";
    $user="u878723730_mysqladmin";
    $password="12345678.Umg";
    $bd="u878723730_RegistroAsiste";

    $conexion = mysqli_connect($server, $user, $password, $bd);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
} 
?>
