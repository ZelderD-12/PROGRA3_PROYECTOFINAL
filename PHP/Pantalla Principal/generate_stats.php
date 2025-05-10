<?php
header('Content-Type: application/json');

$salon = isset($_GET['salon']) ? $_GET['salon'] : '';
$inicio = isset($_GET['inicio']) ? $_GET['inicio'] : '';
$fin = isset($_GET['fin']) ? $_GET['fin'] : '';

// Validar entrada
if (empty($salon) || empty($inicio) || empty($fin)) {
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
}

// Datos de ejemplo - reemplaza con consultas reales a tu base de datos
$response = [
    'salon' => $salon,
    'total_estudiantes' => 30,
    'asistencia_promedio' => 85,
    'dia_max_asistencia' => 'Lunes (90%)',
    'dia_min_asistencia' => 'Viernes (75%)',
    'asistencia_diaria' => [25, 30, 28, 22, 20], // Datos para el gráfico diario
    'asistencia_semanal' => [85, 90, 88, 82, 80], // Datos para el gráfico semanal
    'comparativo_salones' => [
        'labels' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
        'datasets' => [
            [
                'label' => 'Salón 101',
                'data' => [85, 78, 90, 82]
            ],
            [
                'label' => 'Salón 202',
                'data' => [78, 82, 85, 80]
            ]
        ]
    ]
];

echo json_encode($response);
?>