<?php
$salon = isset($_GET['salon']) ? $_GET['salon'] : '101';

// Datos simulados para Python (en un sistema real, estos vendrían de tu base de datos)
$datos_para_python = [
    '101' => [
        ['dia' => 'Lunes', 'asistencia' => 23],
        ['dia' => 'Martes', 'asistencia' => 21],
        ['dia' => 'Miércoles', 'asistencia' => 20],
        ['dia' => 'Jueves', 'asistencia' => 22],
        ['dia' => 'Viernes', 'asistencia' => 18]
    ],
    '202' => [
        ['dia' => 'Lunes', 'asistencia' => 25],
        ['dia' => 'Martes', 'asistencia' => 24],
        ['dia' => 'Miércoles', 'asistencia' => 28],
        ['dia' => 'Jueves', 'asistencia' => 23],
        ['dia' => 'Viernes', 'asistencia' => 20]
    ],
    '303' => [
        ['dia' => 'Lunes', 'asistencia' => 18],
        ['dia' => 'Martes', 'asistencia' => 19],
        ['dia' => 'Miércoles', 'asistencia' => 20],
        ['dia' => 'Jueves', 'asistencia' => 20],
        ['dia' => 'Viernes', 'asistencia' => 19]
    ]
];

// Crear un archivo temporal con los datos
$temp_data_file = tempnam(sys_get_temp_dir(), 'statsdata') . '.json';
file_put_contents($temp_data_file, json_encode($datos_para_python[$salon]));

// Ruta al script de Python (ajusta esto según tu estructura de directorios)
$python_script = 'python/Estadisticas.py';

// Ejecutar Python
$command = "python " . escapeshellarg($python_script) . " " . escapeshellarg($temp_data_file);
$output = shell_exec($command);

// El script de Python debería guardar la imagen en un lugar conocido
$image_path = 'temp/stats_' . $salon . '.png';

// Mostrar la imagen
header('Content-Type: image/png');
readfile($image_path);

// Limpiar
unlink($temp_data_file);
?>