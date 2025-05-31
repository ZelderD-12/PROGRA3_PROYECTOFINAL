<?php
include 'bdconexion.php';

// Lista blanca de tipos válidos para evitar inyección
$tiposValidos = [
    'Estudiantes' => 'Obtener_Estudiantes',
    'Administradores' => 'Obtener_Administradores',
    'Profesores' => 'Obtener_Profesores',
    'Desarrolladores' => 'Obtener_Desarrolladores',
    'Servicios' => 'Obtener_Servicios'
];

if (isset($_GET['tipo']) && array_key_exists($_GET['tipo'], $tiposValidos)) {
    $tipo = $_GET['tipo'];
    $procedure = $tiposValidos[$tipo];

    $sql = "CALL $procedure()";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        $contador = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $contador++ . "</td>";
            echo "<td>" . htmlspecialchars($row['Carnet_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Nombres_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Apellidos_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Correo_Electronico_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Numero_De_Telefono_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Id_Tipo_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Id_Carrera_Usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Seccion_Usuario']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9' style='text-align:center;'>No hay registros para $tipo.</td></tr>";
    }

    $conexion->close();
} else {
    echo "<tr><td colspan='9' style='text-align:center;'>Tipo inválido o sin información.</td></tr>";
}
?>
