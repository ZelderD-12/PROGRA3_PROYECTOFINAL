<?php
include '../Base de Datos/operaciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <button id="toggle-language" class="tech-button">English</button>



    <div class="header">
        <img src="../../imagenes/logo.png" alt="Logo de proyecto" class="logo">
    </div>

    <div class="usuario-imagen" style="text-align: center; margin-top: 20px;">
        <?php
        if (isset($_SESSION['carnetusuario'])) {
            mostrarImagenDesdeSP($_SESSION['carnetusuario']);
        } else {
            echo "<p>⚠️ No se ha iniciado sesión o no hay imagen disponible.</p>";
        }
        ?>
    </div>

    <?php
    function generarYEnviarPDF($datos)
    {
        try {
            extract($datos); // Extrae variables del arreglo

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Correo inválido');
            if (empty($fotoData)) throw new Exception('Foto no recibida');
        } catch (Exception $e) {
            error_log("Error en generarYEnviarPDF: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    ?>

    <button onclick="window.print()" class="tech-button1">Imprimir Carnet</button>

</body>

</html>