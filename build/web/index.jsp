<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('${pageContext.request.contextPath}/imagenes/fondo1.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
       .container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Sombra más intensa para resaltar */
        text-align: center;
        border: 3px solid black; /* Borde negro sólido */
        position: relative; /* Necesario para el pseudo-elemento */
        }


        .hidden {
            display: none;
        }
        .btn {
            margin-top: 10px;
            padding: 10px;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            width: 120px;
        }
        .btn-docente {
            background: red;
        }
        .btn-docente:hover {
            background: darkred;
        }
        .btn-alumno {
            background: blue;
        }
        .btn-alumno:hover {
            background: darkblue;
        }
        .input-field {
            margin-top: 10px;
            padding: 8px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        header {
        position: absolute;
        top: 10px;
        left: 10px;
    
        
}

.logo {
    width: 100px; /* Ajusta el tamaño según sea necesario */
    height: auto;
}

    /* Animación suave de entrada */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
}

h3 {
    margin-bottom: 10px;
    font-size: 18px;
}

.input-field {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

.btn {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    cursor: pointer;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-docente {
    background-color: #007BFF; /* Azul */
}

    .btn-alumno {
    background-color: #FF5733; /* Rojo */
    }

    .btn:hover {
    background-color: #333;
    }

    .hidden {
      display: none;
    }


    </style>
</head>
<body>
    <div class="container">
        <h2>Selecciona tu tipo de usuario</h2>
        <header>
        <img src="${pageContext.request.contextPath}/imagenes/logo.png" alt="Logo de la Empresa" class="logo">
        </header>
        
        <%-- Botones que llaman a métodos JSP --%>
        <form method="post">
            <button type="submit" name="tipoUsuario" value="docente" class="btn btn-docente">Docente</button>
            <button type="submit" name="tipoUsuario" value="alumno" class="btn btn-alumno">Alumno</button>
        </form>
        
        <%-- Formulario de login --%>
        <div id="loginForm" class="<%= request.getParameter("tipoUsuario") == null ? "hidden" : "" %>">
            <h3 id="loginTitle">
                <% if(request.getParameter("tipoUsuario") != null) { %>
                    Login <%= request.getParameter("tipoUsuario").equals("docente") ? "Docente" : "Alumno" %>
                <% } %>
            </h3>
            <form action="procesarLogin.jsp" method="post">
                <input type="hidden" name="tipoUsuario" value="<%= request.getParameter("tipoUsuario") %>">
                <input type="email" name="email" class="input-field" placeholder="Correo electrónico"><br>
                <input type="password" name="password" class="input-field" placeholder="Contraseña"><br>
                <button type="submit" class="btn <%= request.getParameter("tipoUsuario") != null && request.getParameter("tipoUsuario").equals("docente") ? "btn-docente" : "btn-alumno" %>">
                    Ingresar
                </button>
            </form>
        </div>
    </div>
</body>
</html>