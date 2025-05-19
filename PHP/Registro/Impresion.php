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

    <div class="reactor-ark">
        <div class="reactor-core"></div>
        <div class="reactor-ring"></div>
        <div class="reactor-connections">
            <div class="connection-line" style="width: 70%; top: 50%; left: 50%; transform: rotate(45deg);"></div>
            <div class="connection-line" style="width: 70%; top: 50%; left: 50%; transform: rotate(135deg);"></div>
            <div class="connection-line" style="width: 70%; top: 50%; left: 50%; transform: rotate(225deg);"></div>
            <div class="connection-line" style="width: 70%; top: 50%; left: 50%; transform: rotate(315deg);"></div>
        </div>
        <div class="reactor-dots">
            <div class="reactor-dot" style="top: 10%; left: 50%;"></div>
            <div class="reactor-dot" style="top: 50%; right: 10%;"></div>
            <div class="reactor-dot" style="bottom: 10%; left: 50%;"></div>
            <div class="reactor-dot" style="top: 50%; left: 10%;"></div>
            <div class="reactor-dot" style="top: 15%; left: 15%;"></div>
            <div class="reactor-dot" style="top: 15%; right: 15%;"></div>
            <div class="reactor-dot" style="bottom: 15%; right: 15%;"></div>
            <div class="reactor-dot" style="bottom: 15%; left: 15%;"></div>
        </div>
    </div>

    <div class="container">
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
                
        
    </div>
</body>
</html>