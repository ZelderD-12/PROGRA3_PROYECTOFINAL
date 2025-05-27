<?php
session_start();

if (isset($_SESSION['datos_pdf'])) {
    $datos = $_SESSION['datos_pdf'];
} else {
    echo "No se encontraron datos.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/carnet.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</head>

<body>
    <button id="return-login" class="tech-button">Login</button>

    <div class="carnet-container">
        <div class="carnet-header-logos">
            <img src="../../imagenes/logoumg.png" alt="Logo UMG" class="logo-left">

            <!-- Texto central -->
            <div class="carnet-title">CARNET</div>

            <img src="../../imagenes/logo.png" alt="Logo Proyecto" class="logo-right">
        </div>

        <div class="carnet-body">
            <div class="photo-container">
                <img src="<?php echo $datos['fotoData']; ?>" alt="Foto del Usuario" class="user-photo">
            </div>

            <div class="vertical-arrow"></div>

            <div class="info-container">
                <h1><?php echo htmlspecialchars($datos['nombres']) . " " . htmlspecialchars($datos['apellidos']); ?></h1>
                <div class="underline"></div>
                <h3><?php echo htmlspecialchars($datos['carrera']); ?></h3>
                <p><strong>Sección:</strong> <?php echo htmlspecialchars($datos['seccion']); ?></p>
                <p><strong>Carnet:</strong> <?php echo htmlspecialchars($datos['carnet']); ?></p>
                <p><strong>Celular:</strong> <?php echo htmlspecialchars($datos['celular']); ?></p>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($datos['tipo']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($datos['email']); ?></p>
            </div>
        </div>

        <div class="fecha-hora" id="fechaHoraJS"></div>
    </div>
    <button id="btnDescargarPDF" class="tech-button1">Imprimir Carnet</button>

    <script>
        // Redirigir al login
        document.getElementById("return-login").addEventListener("click", function() {
            window.location.href = "../../index.php";
        });

        //Fecha y Hora Actual del Navegador del Usuario
        function actualizarFechaHora() {
            const ahora = new Date();

            const opcionesFecha = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            };

            let horas = ahora.getHours();
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            const ampm = horas >= 12 ? 'p.m.' : 'a.m.';

            horas = horas % 12;
            horas = horas ? horas : 12;

            const fechaFormateada = ahora.toLocaleDateString('es-ES', opcionesFecha);
            const horaFormateada = `${horas}:${minutos}:${segundos} ${ampm}`;

            return `${fechaFormateada} ${horaFormateada}`;
        }

        // Mostrar fecha y hora solo una vez al cargar
        const fechaHora = actualizarFechaHora();
        document.getElementById("fechaHoraJS").textContent = fechaHora;


        //Función para descargar carnet
        document.getElementById("btnDescargarPDF").addEventListener("click", () => {
            const element = document.querySelector(".carnet-container");

            const opt = {
                margin: 0,
                filename: 'Carnet_<?php echo $datos["carnet"]; ?>.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>

</body>

</html>