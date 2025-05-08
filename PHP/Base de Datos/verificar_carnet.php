<?php
include 'bdconexion.php'; // o tu archivo correcto

if (isset($_GET['carnet'])) {
    $carnet = $_GET['carnet'];
    
    $conexion->query("CALL BuscarCarnet('$carnet', @existe)");
    $resultado = $conexion->query("SELECT @existe AS existe");
    $fila = $resultado->fetch_assoc();

    echo json_encode(['existe' => $fila['existe'] == 1]);
}
?>
