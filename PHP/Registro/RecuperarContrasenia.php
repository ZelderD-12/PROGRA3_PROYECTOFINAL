<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
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

        <h2><span id="register-title">Recuperar contraseña</span></h2>

        <div id="error-message" class="error-message hidden"></div>

        <div class="form-grid">
            <input type="text" name="carnet" placeholder="Carnet" required class="input-field" maxlength="15" pattern="[0-9]+" title="Solo números">

            <select name="metodo_recuperacion" id="metodo_recuperacion" class="input-field" disabled required onchange="mostrarCampos()">
                <option value="">Seleccionar método de recuperación</option>
                <option value="email">Por correo electrónico</option>
                <option value="telefono">Por teléfono</option>
                <option value="ambos">Por correo y teléfono</option>
            </select>

            <div id="campo_email" style="display: none;">
                <input type="email" name="email" placeholder="Email" class="input-field" maxlength="50" disabled>
            </div>

            <div id="campo_telefono" style="display: none;">
                <input type="text" name="celular" placeholder="Celular" class="input-field" maxlength="8" pattern="[0-9]+" title="Solo números" disabled>
            </div>

            <div id="campos_password" style="display: none;">
                <input type="password" name="password" placeholder="Nueva Contraseña" class="input-field" maxlength="50">
                <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" class="input-field" maxlength="50">
            </div>
        </div>

        <div class="form-actions">
            <button type="button" name="recuperar" class="btn" id="btn_recuperar" disabled>Recuperar Contraseña</button>
            <button type="reset" class="btn btn-secondary" onclick="resetForm()">Limpiar</button>
        </div>
    </div>


    <script>
        /* ---------- helpers de la interfaz ---------- */
        function setPasswordRequired() {
            document.querySelector('input[name="password"]').required = true;
            document.querySelector('input[name="confirm_password"]').required = true;
        }

        function clearPasswordRequired() {
            document.querySelector('input[name="password"]').required = false;
            document.querySelector('input[name="confirm_password"]').required = false;
        }

        /* --------- datos obtenidos del SP ---------- */
        let datosCarnet = null; // {correo:'...', celular:'...'} ó null

        /* ---------- DOMContentLoaded ---------- */
        document.addEventListener('DOMContentLoaded', () => {

            /* elementos que usaremos varias veces */
            const carnetInput = document.querySelector('input[name="carnet"]');
            window.emailInput = document.querySelector('input[name="email"]'); // globales p/ uso en mostrarCampos
            window.celularInput = document.querySelector('input[name="celular"]');
            window.metodoSelect = document.getElementById('metodo_recuperacion');
            window.campoEmail = document.getElementById('campo_email');
            window.campoTelefono = document.getElementById('campo_telefono');
            window.camposPassword = document.getElementById('campos_password');
            window.btnRecuperar = document.getElementById('btn_recuperar');

            /* solo números en carnet y celular */
            [carnetInput, celularInput].forEach(inp =>
                inp.addEventListener('input', () => inp.value = inp.value.replace(/\D/g, ''))
            );

            /* ---------- mostrar / ocultar campos según selección ---------- */
            const mostrarCampos = () => {
                const metodo = metodoSelect.value;
                campoEmail.style.display = 'none';
                campoTelefono.style.display = 'none';
                camposPassword.style.display = 'none';
                clearPasswordRequired();
                emailInput.value = '';
                celularInput.value = '';

                if (!metodo) {
                    btnRecuperar.disabled = true;
                    return;
                }

                if (metodo === 'email' || metodo === 'ambos') {
                    campoEmail.style.display = 'block';
                    if (datosCarnet) emailInput.value = datosCarnet.correo || '';
                }
                if (metodo === 'telefono' || metodo === 'ambos') {
                    campoTelefono.style.display = 'block';
                    if (datosCarnet) celularInput.value = datosCarnet.celular || '';
                }
                camposPassword.style.display = 'block';
                setPasswordRequired();
                btnRecuperar.disabled = false;
            }

            /* ---- BUSCAR carnet al perder el foco ---- */
            carnetInput.addEventListener('blur', async () => {
                const carnet = carnetInput.value.trim();
                if (!carnet) return;

                try {
                    const res = await fetch(`../Base de Datos/buscar_datos_carnet.php?carnet=${encodeURIComponent(carnet)}`);
                    const data = await res.json();

                    if (data.correo || data.celular) {
                        datosCarnet = data; // guarda para uso posterior
                        metodoSelect.disabled = false; // habilita el combo
                        mostrarCampos(); // actualiza campos según selección actual
                    } else {
                        datosCarnet = null;
                        metodoSelect.value = ''; // resetea selección
                        metodoSelect.disabled = true;
                        mostrarCampos(); // limpia campos
                        alert('Carnet no encontrado en la base de datos.');
                    }
                } catch (e) {
                    console.error('Error consultando carnet:', e);
                }
            });

            metodoSelect.addEventListener('change', mostrarCampos);

        });

        /*-------- Actualizar Contraseña ------*/
    </script>
</body>

</html>