<?php
include 'operaciones.php';
$idSalon = isset($_GET['idSalon']) ? intval($_GET['idSalon']) : 0;
$ubicacionesBD = obtenerUbicacionesPorSalon($idSalon);
function transformarUbicaciones($ubicacionesBD) {
    $ubicacionesPlanas = [];
    if (isset($ubicacionesBD['ubicaciones'])) {
        foreach ($ubicacionesBD['ubicaciones'] as $ubicacion) {
            $ubicacionesPlanas[] = [
                'idEdificio' => (int)$ubicacion['idEdificio'],
                'edificio'   => $ubicacion['edificio'],
                'idPuerta'   => (int)$ubicacion['idPuerta'],
                'puerta'     => $ubicacion['puerta'],
                'nivel'      => (int)$ubicacion['nivel'],
                'idSalon'    => (int)$ubicacion['idSalon'],
                'salon'      => $ubicacion['salon']
            ];
        }
    }
    return $ubicacionesPlanas;
}
$ubicacionesTransformadas = transformarUbicaciones($ubicacionesBD);
echo json_encode([
    'ubicaciones' => $ubicacionesTransformadas,
    'usuarios' => []
]);
?>