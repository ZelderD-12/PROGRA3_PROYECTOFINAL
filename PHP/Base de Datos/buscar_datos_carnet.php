<?php
ini_set('display_errors', 0);
error_reporting(0);

include "bdconexion.php";

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['carnet'])) {
    $carnet = $_GET['carnet'];

    // Evitar inyección
    $carnet = $conexion->real_escape_string($carnet);

    // Prepara y ejecuta el procedimiento almacenado
    if ($stmt = $conexion->prepare("CALL selectCarnet(?, @correo, @celular)")) {
        $stmt->bind_param("s", $carnet);
        $stmt->execute();
        $stmt->close();

        // Obtener los valores OUT
        $result = $conexion->query("SELECT @correo AS correo, @celular AS celular");
        $data = $result->fetch_assoc();

        if ($data && $data['correo'] !== 'nulo') {
            echo json_encode([
                "correo" => $data['correo'],
                "celular" => $data['celular']
            ]);
        } else {
            echo json_encode(new stdClass()); // objeto vacío
        }
    } else {
        echo json_encode(["error" => "Error en la consulta."]);
    }

    $conexion->close();
} else {
    echo json_encode(["error" => "No se recibió el parámetro carnet."]);
}
?>
