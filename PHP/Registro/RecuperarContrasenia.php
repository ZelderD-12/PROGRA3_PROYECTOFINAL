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
            
            <select name="metodo_recuperacion" id="metodo_recuperacion" class="input-field" required onchange="mostrarCampos()">
                <option value="">Seleccionar método de recuperación</option>
                <option value="email">Por correo electrónico</option>
                <option value="telefono">Por teléfono</option>
                <option value="ambos">Por correo y teléfono</option>
            </select>

            <div id="campo_email" style="display: none;">
                <input type="email" name="email" placeholder="Email" class="input-field" maxlength="50">
            </div>

            <div id="campo_telefono" style="display: none;">
                <input type="text" name="celular" placeholder="Celular" class="input-field" maxlength="8" pattern="[0-9]+" title="Solo números">
            </div>

            <div id="campos_password" style="display: none;">
                <input type="password" name="password" placeholder="Nueva Contraseña" class="input-field" maxlength="50">
                <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" class="input-field" maxlength="50">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="recuperar" class="btn" id="btn_recuperar" disabled>Recuperar Contraseña</button>
            <button type="reset" class="btn btn-secondary" onclick="resetForm()">Limpiar</button>
        </div>
    </div>

    <script>
        // Objeto principal para manejar el formulario
        const FormularioRecuperacion = {
            // Obtener datos del formulario------------------------------------------------------------------------------------
            obtenerDatos: function() {
                return {
                    carnet: document.querySelector('input[name="carnet"]')?.value || '',
                    email: document.querySelector('input[name="email"]')?.value || '',
                    telefono: document.querySelector('input[name="celular"]')?.value || '',
                    password: document.querySelector('input[name="password"]')?.value || '',
                    confirmPassword: document.querySelector('input[name="confirm_password"]')?.value || '',
                    metodoRecuperacion: document.getElementById('metodo_recuperacion')?.value || ''
                };
            },
            //-----------------------------------------------------------------------------------------------------------------------------
            // Validar los datos del formulario
            validarDatos: function() {
                const datos = this.obtenerDatos();
                const errores = [];
                
                if (!datos.carnet) errores.push('Carnet es requerido');
                if (!datos.metodoRecuperacion) errores.push('Método de recuperación es requerido');
                if (!datos.password) errores.push('Contraseña es requerida');
                if (datos.password !== datos.confirmPassword) errores.push('Las contraseñas no coinciden');
                
                // Validaciones específicas según el método
                if (datos.metodoRecuperacion === 'email' || datos.metodoRecuperacion === 'ambos') {
                    if (!datos.email) errores.push('Email es requerido');
                }
                if (datos.metodoRecuperacion === 'telefono' || datos.metodoRecuperacion === 'ambos') {
                    if (!datos.telefono) errores.push('Teléfono es requerido');
                }
                
                return {
                    valido: errores.length === 0,
                    errores: errores
                };
            }
        };

        // Mostrar/ocultar campos según el método seleccionado
        function mostrarCampos() {
            const metodo = document.getElementById('metodo_recuperacion').value;
            const campoEmail = document.getElementById('campo_email');
            const campoTelefono = document.getElementById('campo_telefono');
            const camposPassword = document.getElementById('campos_password');
            const btnRecuperar = document.getElementById('btn_recuperar');

            // Ocultar todos los campos
            campoEmail.style.display = 'none';
            campoTelefono.style.display = 'none';
            camposPassword.style.display = 'none';
            
            // Remover required de todos los campos
            document.querySelector('input[name="email"]').removeAttribute('required');
            document.querySelector('input[name="celular"]').removeAttribute('required');
            document.querySelector('input[name="password"]').removeAttribute('required');
            document.querySelector('input[name="confirm_password"]').removeAttribute('required');

            // Mostrar campos según selección
            if (metodo === 'email') {
                campoEmail.style.display = 'block';
                camposPassword.style.display = 'block';
                document.querySelector('input[name="email"]').setAttribute('required', 'required');
                setPasswordRequired();
                btnRecuperar.disabled = false;
            } else if (metodo === 'telefono') {
                campoTelefono.style.display = 'block';
                camposPassword.style.display = 'block';
                document.querySelector('input[name="celular"]').setAttribute('required', 'required');
                setPasswordRequired();
                btnRecuperar.disabled = false;
            } else if (metodo === 'ambos') {
                campoEmail.style.display = 'block';
                campoTelefono.style.display = 'block';
                camposPassword.style.display = 'block';
                document.querySelector('input[name="email"]').setAttribute('required', 'required');
                document.querySelector('input[name="celular"]').setAttribute('required', 'required');
                setPasswordRequired();
                btnRecuperar.disabled = false;
            } else {
                btnRecuperar.disabled = true;
            }
        }

        // Función auxiliar para establecer required en campos de contraseña
        function setPasswordRequired() {
            document.querySelector('input[name="password"]').setAttribute('required', 'required');
            document.querySelector('input[name="confirm_password"]').setAttribute('required', 'required');
        }

        // Limpiar formulario
        function resetForm() {
            document.getElementById('metodo_recuperacion').value = '';
            document.getElementById('campo_email').style.display = 'none';
            document.getElementById('campo_telefono').style.display = 'none';
            document.getElementById('campos_password').style.display = 'none';
            document.getElementById('btn_recuperar').disabled = true;
            document.getElementById('error-message').classList.add('hidden');
        }

        // Verificar si el carnet existe en la BD
        async function verificarCarnet(carnet) {
            try {
                const response = await fetch(`../Base de Datos/verificar_carnet.php?carnet=${carnet}&accion=verificar_existe`);
                const data = await response.json();
                return data.existe;
            } catch (error) {
                console.error('Error al verificar carnet:', error);
                return false;
            }
        }

        // Configurar eventos al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const carnetInput = document.querySelector('input[name="carnet"]');
            const celularInput = document.querySelector('input[name="celular"]');

            // Solo números en carnet y celular
            [carnetInput, celularInput].forEach(input => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
                });
            });
        });
    </script>
</body>
</html>