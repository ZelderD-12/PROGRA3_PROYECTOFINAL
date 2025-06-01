<?php
include 'bdconexion.php';
header('Content-Type: application/json');

if (!isset($_POST['carnet'])) {
    echo json_encode(['error' => 'El parámetro carnet es requerido']);
    exit();
}

$carnet = $_POST['carnet'];

// Llamar al procedimiento almacenado de eliminación
$stmt = $conexion->prepare("CALL EliminarUsuarios_AdministrarUsuarios(?)");
$stmt->bind_param("s", $carnet);
$stmt->execute();

// Verificar si se afectó alguna fila
if ($stmt->affected_rows > 0) {
    $response = ['success' => 'Usuario eliminado correctamente'];
} else {
    $response = ['error' => 'No se encontró el usuario con ese carnet'];
}

// Liberar resultados
while ($conexion->more_results()) {
    $conexion->next_result();
}

$stmt->close();
$conexion->close();

echo json_encode($response);
?>