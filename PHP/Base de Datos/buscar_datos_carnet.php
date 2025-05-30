<?php
include "bdconexion.php";

if (isset($_GET['carnet'])) {
    $carnet = $_GET['carnet'];

    // Preparar la llamada al procedimiento almacenado
    $stmt = $conexion->prepare("CALL selectCarnet(?, @correo, @celular)");
    $stmt->bind_param("s", $carnet);
    $stmt->execute();
    $stmt->close();

    // Ahora obtenemos los valores OUT
    $result = $conexion->query("SELECT @correo AS correo, @celular AS celular");
    $data = $result->fetch_assoc();

    if ($data['correo'] !== 'nulo') {
        echo json_encode([
            "correo" => $data['correo'],
            "celular" => $data['celular']
        ]);
    } else {
        echo json_encode([]);
    }

    $conexion->close();
}
?>
