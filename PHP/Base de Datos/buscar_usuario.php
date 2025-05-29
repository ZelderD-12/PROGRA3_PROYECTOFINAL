<?php
include 'bdconexion.php';
header('Content-Type: application/json');

if (!isset($_GET['carnet'])) {
    echo json_encode(['error' => 'El parámetro carnet es requerido']);
    exit();
}

$carnet = $_GET['carnet'];

// Llamar al procedimiento almacenado
$stmt = $conexion->prepare("CALL BuscarUsuarios_AdministrarUsuarios(?)");
$stmt->bind_param("s", $carnet);
$stmt->execute();

$result = $stmt->get_result();
$usuarios = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = [
            'Carnet_Usuario' => $row['Carnet_Usuario'],
            'Nombres_Usuario' => $row['Nombres_Usuario'],
            'Apellidos_Usuario' => $row['Apellidos_Usuario'],
            'Correo_Electronico_Usuario' => $row['Correo_Electronico_Usuario'],
            'Numero_De_Telefono_Usuario' => $row['Numero_De_Telefono_Usuario'],
            'Tipo_De_Usuario' => $row['Tipo_De_Usuario'],
            'Nombre_Carrera' => $row['Nombre_Carrera'],
            'Seccion_Usuario' => $row['Seccion_Usuario']
        ];
    }
}

// Liberar resultados
while ($conexion->more_results()) {
    $conexion->next_result();
}

$stmt->close();
$conexion->close();

echo json_encode(['usuarios' => $usuarios]);
?>