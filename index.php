<?php include 'PHP/Base de Datos/operaciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <button id="toggle-language" class="tech-button">English</button>

    <div class="reactor-ark">
        <!-- Contenido del reactor (igual que antes) -->
    </div>

    <div class="container">
        <div class="header">
            <img src="imagenes/logo.png" alt="Logo de proyecto" class="logo">
        </div>
                
        <h2><span id="login-title">Inicio de Sesión</span></h2>
                
        <div id="error-message" class="error-message hidden"></div>
                
        <form id="login-form" action="PHP/Base de Datos/operaciones.php" method="POST">
            <input type="email" id="email" name="email" class="input-field" placeholder="Correo electrónico" required>
            <input type="password" id="password" name="password" class="input-field" placeholder="Contraseña" required>
            <button type="submit" class="btn" id="login-btn" name="login">Iniciar sesión</button>
        </form>
        <a href="PHP/Registro/RecuperarContrasenia.php" >Recuperar Contraseñia</a>
    </div>
 
    <script>
        // Cambiador de idioma
        document.getElementById("toggle-language").addEventListener("click", function() {
            const isEnglish = document.documentElement.lang === "en";
            if (isEnglish) {
                document.documentElement.lang = "es";
                this.textContent = "English";
                document.getElementById("login-title").textContent = "Inicio de Sesión";
                document.getElementById("email").placeholder = "Correo electrónico";
                document.getElementById("password").placeholder = "Contraseña";
                document.getElementById("login-btn").textContent = "Iniciar sesión";
            } else {
                document.documentElement.lang = "en";
                this.textContent = "Español";
                document.getElementById("login-title").textContent = "Login";
                document.getElementById("email").placeholder = "Email";
                document.getElementById("password").placeholder = "Password";
                document.getElementById("login-btn").textContent = "Login";
            }
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