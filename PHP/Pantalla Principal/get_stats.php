<?php
header('Content-Type: application/json');

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'asistencia';
$salon = isset($_GET['salon']) ? $_GET['salon'] : '';
$inicio = isset($_GET['inicio']) ? $_GET['inicio'] : '';
$fin = isset($_GET['fin']) ? $_GET['fin'] : '';

// Validar entrada
if (empty($inicio) || empty($fin)) {
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
}

// Datos de ejemplo mejorados con más información
$response = [
    'fecha_inicio' => $inicio,
    'fecha_fin' => $fin
];

// Datos comunes para todos los salones
$salonesData = [
    '101' => [
        'nombre' => 'Salón 101',
        'total_estudiantes' => 30,
        'estudiantes' => [
            ['id' => 1, 'nombre' => 'Ana Pérez', 'asistencias' => 28, 'inasistencias' => 2],
            ['id' => 2, 'nombre' => 'Carlos López', 'asistencias' => 25, 'inasistencias' => 5],
            ['id' => 3, 'nombre' => 'María García', 'asistencias' => 30, 'inasistencias' => 0],
            // ... más estudiantes
        ],
        'dias_semana' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        'asistencias_diarias' => [25, 28, 27, 23, 20],
        'asistencia_por_hora' => [85, 90, 88, 82, 80, 75, 70],
        'horas_dia' => ['7-8', '8-9', '9-10', '10-11', '11-12', '12-13', '13-14']
    ],
    '202' => [
        'nombre' => 'Salón 202',
        'total_estudiantes' => 25,
        'estudiantes' => [
            ['id' => 4, 'nombre' => 'Juan Martínez', 'asistencias' => 22, 'inasistencias' => 8],
            ['id' => 5, 'nombre' => 'Lucía Ramírez', 'asistencias' => 24, 'inasistencias' => 6],
            ['id' => 6, 'nombre' => 'Pedro Sánchez', 'asistencias' => 20, 'inasistencias' => 10],
            // ... más estudiantes
        ],
        'dias_semana' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        'asistencias_diarias' => [20, 22, 21, 19, 18],
        'asistencia_por_hora' => [75, 80, 85, 78, 75, 70, 65],
        'horas_dia' => ['7-8', '8-9', '9-10', '10-11', '11-12', '12-13', '13-14']
    ],
    '303' => [
        'nombre' => 'Salón 303',
        'total_estudiantes' => 35,
        'estudiantes' => [
            ['id' => 7, 'nombre' => 'Sofía Castro', 'asistencias' => 33, 'inasistencias' => 2],
            ['id' => 8, 'nombre' => 'Diego Fernández', 'asistencias' => 30, 'inasistencias' => 5],
            ['id' => 9, 'nombre' => 'Valeria Morales', 'asistencias' => 35, 'inasistencias' => 0],
            // ... más estudiantes
        ],
        'dias_semana' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        'asistencias_diarias' => [32, 34, 33, 30, 28],
        'asistencia_por_hora' => [90, 92, 95, 90, 88, 85, 80],
        'horas_dia' => ['7-8', '8-9', '9-10', '10-11', '11-12', '12-13', '13-14']
    ]
];

switch($tipo) {
    case 'asistencia':
        if (!array_key_exists($salon, $salonesData)) {
            echo json_encode(['error' => 'Salón no encontrado']);
            exit;
        }
        
        $salonData = $salonesData[$salon];
        $estudianteTop = array_reduce($salonData['estudiantes'], function($carry, $item) {
            return ($carry === null || $item['asistencias'] > $carry['asistencias']) ? $item : $carry;
        });
        
        $response = array_merge($response, [
            'salon' => $salonData['nombre'],
            'total_estudiantes' => $salonData['total_estudiantes'],
            'asistencia_promedio' => round(array_sum($salonData['asistencias_diarias']) / count($salonData['asistencias_diarias']) / $salonData['total_estudiantes'] * 100, 2),
            'dia_max_asistencia' => $salonData['dias_semana'][array_search(max($salonData['asistencias_diarias']), $salonData['asistencias_diarias'])],
            'max_asistencia' => max($salonData['asistencias_diarias']),
            'dia_min_asistencia' => $salonData['dias_semana'][array_search(min($salonData['asistencias_diarias']), $salonData['asistencias_diarias'])],
            'min_asistencia' => min($salonData['asistencias_diarias']),
            'dias_semana' => $salonData['dias_semana'],
            'asistencias_diarias' => $salonData['asistencias_diarias'],
            'asistencia_por_hora' => $salonData['asistencia_por_hora'],
            'horas_dia' => $salonData['horas_dia'],
            'estudiante_top' => $estudianteTop,
            'estudiantes' => $salonData['estudiantes']
        ]);
        break;
        
    case 'comparativa-salones':
        $porcentajes = [];
        $asistencias = [];
        foreach ($salonesData as $salonInfo) {
            $promedio = round(array_sum($salonInfo['asistencias_diarias']) / count($salonInfo['asistencias_diarias']) / $salonInfo['total_estudiantes'] * 100, 2);
            $porcentajes[] = $promedio;
            $asistencias[] = array_sum($salonInfo['asistencias_diarias']);
        }
        
        $maxIndex = array_search(max($porcentajes), $porcentajes);
        $minIndex = array_search(min($porcentajes), $porcentajes);
        
        $response = array_merge($response, [
            'total_estudiantes' => array_sum(array_column($salonesData, 'total_estudiantes')),
            'salon_max_asistencia' => $salonesData[array_keys($salonesData)[$maxIndex]]['nombre'],
            'max_asistencia' => $porcentajes[$maxIndex],
            'salon_min_asistencia' => $salonesData[array_keys($salonesData)[$minIndex]]['nombre'],
            'min_asistencia' => $porcentajes[$minIndex],
            'salones' => array_column($salonesData, 'nombre'),
            'porcentajes_asistencia' => $porcentajes,
            'total_asistencias' => $asistencias
        ]);
        break;
        
    case 'comparativa-docentes':
        $docentes = [
            ['nombre' => 'Docente Pérez', 'asistencia' => 92, 'estudiantes' => 30, 'salon' => '101'],
            ['nombre' => 'Docente López', 'asistencia' => 85, 'estudiantes' => 25, 'salon' => '202'],
            ['nombre' => 'Docente García', 'asistencia' => 78, 'estudiantes' => 35, 'salon' => '303']
        ];
        
        $porcentajes = array_column($docentes, 'asistencia');
        $maxIndex = array_search(max($porcentajes), $porcentajes);
        $minIndex = array_search(min($porcentajes), $porcentajes);
        
        $response = array_merge($response, [
            'docente_max_asistencia' => $docentes[$maxIndex]['nombre'],
            'max_asistencia' => $docentes[$maxIndex]['asistencia'],
            'docente_min_asistencia' => $docentes[$minIndex]['nombre'],
            'min_asistencia' => $docentes[$minIndex]['asistencia'],
            'diferencia' => $docentes[$maxIndex]['asistencia'] - $docentes[$minIndex]['asistencia'],
            'docentes' => array_column($docentes, 'nombre'),
            'porcentajes_asistencia' => $porcentajes,
            'estudiantes_por_docente' => array_column($docentes, 'estudiantes'),
            'salones_docentes' => array_column($docentes, 'salon')
        ]);
        break;
}

echo json_encode($response);
?>