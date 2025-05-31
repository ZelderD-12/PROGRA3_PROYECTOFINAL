<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include "bdconexion.php";

// Validar datos
if (
    isset($_POST['carnet']) &&
    isset($_POST['password']) &&
    isset($_POST['confirm_password']) &&
    $_POST['password'] === $_POST['confirm_password']
) {
    $carnet = $conexion->real_escape_string($_POST['carnet']);
    $password = $conexion->real_escape_string($_POST['password']);

    // Llama al SP para actualizar la contraseña
    if ($stmt = $conexion->prepare("CALL resetPass(?, ?)")) {
        $stmt->bind_param("ss", $password, $carnet);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["success" => true, "message" => "Contraseña actualizada con éxito."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error en el procedimiento."]);
    }

    $conexion->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Datos inválidos o contraseñas no coinciden."
    ]);
}
?>
