<?php include 'PHP/Base de Datos/operaciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        /* Estilos adicionales para el toggle y formulario de registro */
        .toggle-container {
            text-align: center;
            margin: 1rem 0;
        }
        
        .toggle-btn {
            background: transparent;
            color: var(--neon-accent);
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: underline;
            transition: var(--transition);
            padding: 0.5rem;
        }
        
        .toggle-btn:hover {
            text-shadow: 0 0 8px var(--neon-accent);
        }
        
        #register-section {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }
        
        .form-row {
            margin-bottom: 1rem;
        }
        
        .face-recognition-container {
            margin: 1.5rem 0;
            text-align: center;
        }
        
        .video-container {
            position: relative;
            width: 320px;
            height: 240px;
            margin: 0 auto;
            border: 2px solid var(--neon-accent);
            border-radius: 4px;
            overflow: hidden;
            background: var(--tech-gray);
        }
        
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .face-controls {
            margin-top: 1rem;
        }
        
        .face-btn {
            padding: 0.75rem 1.5rem;
            background: rgba(26, 29, 43, 0.8);
            color: var(--neon-accent);
            border: 1px solid var(--neon-accent);
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            margin: 0 0.5rem;
        }
        
        .face-btn:hover {
            background: rgba(0, 200, 208, 0.1);
            box-shadow: 0 0 10px rgba(0, 200, 208, 0.3);
        }
        
        .switch-form-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--neon-accent);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <button id="toggle-language" class="tech-button">English</button>

    <div class="reactor-ark">
        <!-- Contenido del reactor -->
    </div>

    <div class="container">
        <div class="header">
            <img src="imagenes/logo.png" alt="Logo de proyecto" class="logo">
        </div>
        
        <div class="toggle-container">
            <button id="form-toggle" class="toggle-btn">¿No tienes cuenta? Regístrate aquí</button>
        </div>
                
        <!-- Formulario de Login -->
        <div id="login-section">
            <h2><span id="login-title">Inicio de Sesión</span></h2>
                    
            <div id="error-message" class="error-message hidden"></div>
                    
            <form id="login-form" action="PHP/Base de Datos/operaciones.php" method="POST">
                <div class="form-row">
                    <input type="email" id="email" name="email" class="input-field" placeholder="Correo electrónico" required>
                </div>
                <div class="form-row">
                    <input type="password" id="password" name="password" class="input-field" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn" id="login-btn" name="login">Iniciar sesión</button>
            </form>
            <a href="PHP/Registro/RecuperarContrasenia.php" class="switch-form-link">Recuperar Contraseña</a>
        </div>
        
        <!-- Formulario de Registro (oculto inicialmente) -->
        <div id="register-section">
            <h2><span id="register-title">Registro de Usuario</span></h2>
                    
            <div id="register-error-message" class="error-message hidden"></div>
                    
            <form id="register-form" action="PHP/Base de Datos/operaciones.php" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <input type="text" id="nombre" name="nombre" class="input-field" placeholder="Nombre completo" required>
                </div>
                
                <div class="form-row">
                    <input type="email" id="register-email" name="email" class="input-field" placeholder="Correo electrónico" required>
                </div>
                
                <div class="form-row">
                    <input type="tel" id="telefono" name="telefono" class="input-field" placeholder="Número de teléfono (10 dígitos)" pattern="[0-9]{10}" required>
                </div>
                
                <div class="form-row">
                    <input type="password" id="register-password" name="password" class="input-field" placeholder="Contraseña" required>
                </div>
                
                <div class="form-row">
                    <input type="password" id="confirm_password" name="confirm_password" class="input-field" placeholder="Confirmar contraseña" required>
                </div>
                
                <div class="form-row">
                    <select id="tipo_usuario" name="tipo_usuario" class="input-field" required>
                        <option value="">Seleccione tipo de usuario</option>
                        <option value="1">Estudiante</option>
                        <option value="2">Profesor</option>
                        <option value="3">Administrador</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <select id="carrera" name="carrera" class="input-field" required>
                        <option value="">Seleccione carrera</option>
                        <option value="1">Ingeniería en Sistemas</option>
                        <option value="2">Ingeniería Industrial</option>
                        <option value="3">Administración</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <input type="text" id="seccion" name="seccion" class="input-field" placeholder="Sección (ej. A01)" required>
                </div>
                
                <!-- Sección de Reconocimiento Facial -->
                <div class="face-recognition-container">
                    <h3 style="color: var(--neon-accent); margin-bottom: 1rem;">Reconocimiento Facial</h3>
                    <div class="video-container">
                        <video id="video" width="320" height="240" autoplay muted></video>
                        <canvas id="canvas" width="320" height="240"></canvas>
                    </div>
                    <div class="face-controls">
                        <button type="button" id="start-camera" class="face-btn">Iniciar Cámara</button>
                        <button type="button" id="capture-face" class="face-btn" disabled>Capturar Rostro</button>
                        <button type="button" id="retry-capture" class="face-btn" disabled>Reintentar</button>
                    </div>
                    <div id="face-status" style="margin-top: 1rem; color: var(--neon-accent);"></div>
                </div>
                
                <input type="hidden" id="descriptor_facial" name="descriptor_facial">
                <input type="hidden" id="puntos_faciales" name="puntos_faciales">
                <input type="hidden" id="imagen_referencia" name="imagen_referencia">
                <input type="hidden" id="angulo_captura" name="angulo_captura">
                
                <button type="submit" class="btn" id="register-btn" name="register" disabled>Registrarse</button>
            </form>
            <a href="#" id="back-to-login" class="switch-form-link">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>

    <script>
        // Variables globales
        let stream = null;
        let faceDescriptor = null;
        let faceImageData = null;
        let faceLandmarks = null;
        let faceAngle = null;

        // Cargar modelos de Face-API.js
        async function loadModels() {
            await faceapi.nets.tinyFaceDetector.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            console.log('Modelos cargados');
        }

        // Iniciar cámara
        async function startCamera() {
            try {
                const video = document.getElementById('video');
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: 320, 
                        height: 240,
                        facingMode: 'user' 
                    }, 
                    audio: false 
                });
                video.srcObject = stream;
                
                document.getElementById('start-camera').disabled = true;
                document.getElementById('capture-face').disabled = false;
                document.getElementById('face-status').textContent = 'Cámara activada. Por favor, mire directamente a la cámara.';
                
                // Detección de rostros en tiempo real
                setInterval(async () => {
                    if (!stream) return;
                    
                    const detections = await faceapi.detectAllFaces(
                        video, 
                        new faceapi.TinyFaceDetectorOptions()
                    ).withFaceLandmarks();
                    
                    const canvas = document.getElementById('canvas');
                    const context = canvas.getContext('2d');
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    
                    if (detections.length > 0) {
                        faceapi.draw.drawDetections(canvas, detections);
                        faceapi.draw.drawFaceLandmarks(canvas, detections);
                    }
                }, 100);
                
            } catch (err) {
                console.error('Error al acceder a la cámara:', err);
                document.getElementById('face-status').textContent = 'Error al acceder a la cámara. Por favor, permita el acceso.';
            }
        }

        // Capturar rostro
        async function captureFace() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            
            // Dibujar el frame actual en el canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Obtener descriptor facial
            const detections = await faceapi.detectAllFaces(
                canvas, 
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptors();
            
            if (detections.length === 0) {
                document.getElementById('face-status').textContent = 'No se detectó ningún rostro. Por favor, intente nuevamente.';
                return;
            }
            
            // Tomar el primer rostro detectado
            const detection = detections[0];
            faceDescriptor = detection.descriptor;
            faceLandmarks = detection.landmarks;
            
            // Calcular ángulo de la cabeza (simplificado)
            const jawOutline = faceLandmarks.getJawOutline();
            const left = jawOutline[0];
            const right = jawOutline[16];
            faceAngle = Math.atan2(right.y - left.y, right.x - left.x) * (180 / Math.PI);
            
            // Obtener imagen de referencia (base64)
            faceImageData = canvas.toDataURL('image/png');
            
            // Actualizar campos ocultos
            document.getElementById('descriptor_facial').value = JSON.stringify(faceDescriptor);
            document.getElementById('puntos_faciales').value = JSON.stringify(faceLandmarks.positions);
            document.getElementById('imagen_referencia').value = faceImageData;
            document.getElementById('angulo_captura').value = faceAngle;
            
            // Habilitar botón de registro
            document.getElementById('register-btn').disabled = false;
            document.getElementById('capture-face').disabled = true;
            document.getElementById('retry-capture').disabled = false;
            document.getElementById('face-status').textContent = 'Rostro capturado correctamente. Puede continuar con el registro.';
            
            // Detener la cámara
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        // Reintentar captura
        function retryCapture() {
            document.getElementById('canvas').getContext('2d').clearRect(0, 0, 320, 240);
            document.getElementById('register-btn').disabled = true;
            document.getElementById('capture-face').disabled = true;
            document.getElementById('retry-capture').disabled = true;
            document.getElementById('face-status').textContent = '';
            
            // Limpiar campos ocultos
            document.getElementById('descriptor_facial').value = '';
            document.getElementById('puntos_faciales').value = '';
            document.getElementById('imagen_referencia').value = '';
            document.getElementById('angulo_captura').value = '';
            
            // Reiniciar cámara
            document.getElementById('start-camera').disabled = false;
        }

        // Event Listeners
        document.getElementById('start-camera').addEventListener('click', startCamera);
        document.getElementById('capture-face').addEventListener('click', captureFace);
        document.getElementById('retry-capture').addEventListener('click', retryCapture);

        // Cargar modelos al iniciar
        document.addEventListener('DOMContentLoaded', () => {
            loadModels().then(() => {
                console.log('Modelos listos');
            }).catch(err => {
                console.error('Error cargando modelos:', err);
            });
        });

        // Cambiador de idioma
        document.getElementById("toggle-language").addEventListener("click", function() {
            const isEnglish = document.documentElement.lang === "en";
            if (isEnglish) {
                document.documentElement.lang = "es";
                this.textContent = "English";
                
                // Textos login
                document.getElementById("login-title").textContent = "Inicio de Sesión";
                document.getElementById("email").placeholder = "Correo electrónico";
                document.getElementById("password").placeholder = "Contraseña";
                document.getElementById("login-btn").textContent = "Iniciar sesión";
                document.querySelector("a[href='PHP/Registro/RecuperarContrasenia.php']").textContent = "Recuperar Contraseña";
                
                // Textos registro
                document.getElementById("register-title").textContent = "Registro de Usuario";
                document.getElementById("nombre").placeholder = "Nombre completo";
                document.getElementById("register-email").placeholder = "Correo electrónico";
                document.getElementById("telefono").placeholder = "Número de teléfono (10 dígitos)";
                document.getElementById("register-password").placeholder = "Contraseña";
                document.getElementById("confirm_password").placeholder = "Confirmar contraseña";
                document.getElementById("seccion").placeholder = "Sección (ej. A01)";
                document.getElementById("tipo_usuario").options[0].text = "Seleccione tipo de usuario";
                document.getElementById("carrera").options[0].text = "Seleccione carrera";
                document.getElementById("start-camera").textContent = "Iniciar Cámara";
                document.getElementById("capture-face").textContent = "Capturar Rostro";
                document.getElementById("retry-capture").textContent = "Reintentar";
                document.getElementById("register-btn").textContent = "Registrarse";
                document.getElementById("back-to-login").textContent = "¿Ya tienes cuenta? Inicia sesión";
                
                // Texto toggle
                document.getElementById("form-toggle").textContent = "¿No tienes cuenta? Regístrate aquí";
            } else {
                document.documentElement.lang = "en";
                this.textContent = "Español";
                
                // Textos login
                document.getElementById("login-title").textContent = "Login";
                document.getElementById("email").placeholder = "Email";
                document.getElementById("password").placeholder = "Password";
                document.getElementById("login-btn").textContent = "Login";
                document.querySelector("a[href='PHP/Registro/RecuperarContrasenia.php']").textContent = "Forgot Password";
                
                // Textos registro
                document.getElementById("register-title").textContent = "User Registration";
                document.getElementById("nombre").placeholder = "Full name";
                document.getElementById("register-email").placeholder = "Email";
                document.getElementById("telefono").placeholder = "Phone number (10 digits)";
                document.getElementById("register-password").placeholder = "Password";
                document.getElementById("confirm_password").placeholder = "Confirm password";
                document.getElementById("seccion").placeholder = "Section (e.g. A01)";
                document.getElementById("tipo_usuario").options[0].text = "Select user type";
                document.getElementById("carrera").options[0].text = "Select career";
                document.getElementById("start-camera").textContent = "Start Camera";
                document.getElementById("capture-face").textContent = "Capture Face";
                document.getElementById("retry-capture").textContent = "Retry";
                document.getElementById("register-btn").textContent = "Register";
                document.getElementById("back-to-login").textContent = "Already have an account? Login";
                
                // Texto toggle
                document.getElementById("form-toggle").textContent = "Don't have an account? Register here";
            }
        });

        // Toggle entre login y registro
        document.getElementById("form-toggle").addEventListener("click", function() {
            const loginSection = document.getElementById("login-section");
            const registerSection = document.getElementById("register-section");
            
            loginSection.style.display = "none";
            registerSection.style.display = "block";
            this.style.display = "none";
        });
        
        // Volver al login desde registro
        document.getElementById("back-to-login").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("login-section").style.display = "block";
            document.getElementById("register-section").style.display = "none";
            document.getElementById("form-toggle").style.display = "block";
            
            // Limpiar reconocimiento facial si estaba en proceso
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            document.getElementById('canvas').getContext('2d').clearRect(0, 0, 320, 240);
            retryCapture();
        });

        // Manejar errores desde PHP
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = decodeURIComponent(error);
            errorDiv.classList.remove('hidden');
        }
    </script>
</body>
</html>