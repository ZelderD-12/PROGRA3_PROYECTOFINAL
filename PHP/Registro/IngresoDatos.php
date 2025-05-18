<?php include '../Base de Datos/operaciones.php'; ?>
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
        
        <a href="../../index.php" class="back-link">← Volver al login</a>
                
        <h2><span id="register-title">Registro</span></h2>
                
        <div id="error-message" class="error-message hidden"></div>
                
        <form id="register-form" action="../Base de Datos/operaciones.php" method="POST">
            <div class="form-grid">
                <input type="number" name="carnet" placeholder="Carnet" required class="input-field" readonly maxlength="50">
                <input type="text" name="nombres" placeholder="Nombre" required class="input-field" maxlength="100">
                <input type="text" name="apellidos" placeholder="Apellido" required class="input-field" maxlength="100">
                <input type="password" name="password" placeholder="Contraseña" required class="input-field" maxlength="50">
                <input type="text" name="celular" placeholder="Celular" required class="input-field" maxlength="8">
                <input type="email" name="email" placeholder="Email" required class="input-field" maxlength="50">
                <select name="tipouser" required class="input-field" >
                    <option value="" disabled selected>Seleccione un tipo de usuario</option>
                    <?php
                    if (!empty($tipos_usuario)) {
                        foreach ($tipos_usuario as $tipo) {
                            echo "<option value='" . $tipo . "'>" . $tipo . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No se encontraron tipos de usuario</option>";
                    }
                    ?>
        </select>

        <select name="carrera" required class="input-field" >
                    <option value="" disabled selected>Seleccione una carrera</option>
                    <?php
                    if (!empty($carreras)) {
                        foreach ($carreras as $carreer) {
                            echo "<option value='" . $carreer . "'>" . $carreer . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No se encontraron carreras</option>";
                    }
                    ?>
        </select>
        <input type="text" name="seccion" id="seccion" placeholder="Sección" required class="input-field" maxlength="1">
        <input type="hidden" id="foto" name="foto" placeholder="URL de Foto" required class="input-field">
                <div class="camera-section">
    <video id="video" width="320" height="240" autoplay playsinline></video>
    <br>
    <button type="button" id="capturar-foto" class="btn">Tomar Foto</button>
    <br><br>
    <img id="preview" src="" alt="Foto capturada" style="max-width: 100%; display: none;">
    <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
</div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="registrar" class="btn">Registrar</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
            </div>
        </form>
    </div>
    <script>
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
//Script para generar el carnet aleatorio verificando que no exista ya en la bd
    window.addEventListener('DOMContentLoaded', async function () {
    const textbox = document.getElementsByName('carnet')[0];

    async function carnetExiste(carnet) {
        const response = await fetch(`../Base de Datos/verificar_carnet.php?carnet=${carnet}`);
        const data = await response.json();
        return data.existe;
    }

    async function generarCarnetUnico() {
        let carnet, existe;

        do {
            carnet = Math.floor(100000000 + Math.random() * 8999999999);
            existe = await carnetExiste(carnet);
        } while (existe);

        return carnet;
    }

    const carnetUnico = await generarCarnetUnico();
    textbox.value = carnetUnico;
});
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
//Script para manejar spanish and english language
        document.getElementById("toggle-language").addEventListener("click", function() {
            const isEnglish = document.documentElement.lang === "en";
            if (isEnglish) {
                document.documentElement.lang = "es";
                this.textContent = "English";
                document.getElementById("register-title").textContent = "Registro";
                // Actualizar placeholders aquí si es necesario
            } else {
                document.documentElement.lang = "en";
                this.textContent = "Español";
                document.getElementById("register-title").textContent = "Register";
                // Actualizar placeholders aquí si es necesario
            }
        });
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
 //Mayusuculas automaticas en el campo de seccion
    document.getElementById('seccion').addEventListener('input', function(e) {
        // Convertir a mayúsculas automáticamente
        e.target.value = e.target.value.toUpperCase();
    });

//---------------------------------------------------------------------------------------------------------------------------------------------------------------
//Script para validar que la foto haya sido tomada
    document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('register-form');
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');

    formulario.addEventListener('submit', function (event) {
        // Validar que la foto haya sido tomada
        if (!fotoInput.value || preview.style.display === 'none') {
            event.preventDefault(); // Detiene el envío del formulario
            alert('⚠️ Debes tomar una foto antes de registrar el usuario.');
            return false;
        }
    });
});
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
//SOLO NUMEROS CAMPO CELULAR
document.getElementsByName('celular')[0].addEventListener('input', function (e) {
    this.value = this.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
});
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
//Script para enviar los datos registrados a correo
</script>
    <script src=../../Javascript/foto.js></script>
</body>
</html>