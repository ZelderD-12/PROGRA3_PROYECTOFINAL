<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@page import="java.sql.*"%>
<%
    // Variables para mensajes
    String errorMessage = null;
    
    // Procesar formulario de login
    if ("POST".equals(request.getMethod())) {
        String email = request.getParameter("email");
        String password = request.getParameter("password");
        
        if (email != null && password != null && !email.isEmpty() && !password.isEmpty()) {
            Connection conn = null;
            PreparedStatement stmt = null;
            ResultSet rs = null;
            
            try {
                // 1. Cargar driver MySQL
                Class.forName("com.mysql.cj.jdbc.Driver");
                
                // 2. Configurar conexión
                String url = "jdbc:mysql://localhost:3306/tareas?useSSL=false&serverTimezone=UTC";
                String dbUser = "root";
                String dbPass = "Mysql21092004";
                
                // 3. Establecer conexión
                conn = DriverManager.getConnection(url, dbUser, dbPass);
                
                // 4. Consulta SQL segura
                String sql = "SELECT id FROM usuario WHERE correo = ? AND password = ?";
                stmt = conn.prepareStatement(sql);
                stmt.setString(1, email);
                stmt.setString(2, password);
                
                // 5. Ejecutar consulta
                rs = stmt.executeQuery();
                
                if (rs.next()) {
                    // Login exitoso
                    session.setAttribute("user_id", rs.getInt("id"));
                    response.sendRedirect("bienvenido.jsp");
                    return;
                } else {
                    errorMessage = "Credenciales incorrectas. Por favor intente nuevamente.";
                }
            } catch (ClassNotFoundException e) {
                errorMessage = "Error en la configuración del sistema.";
                e.printStackTrace();
            } catch (SQLException e) {
                errorMessage = "Error al conectar con la base de datos.";
                e.printStackTrace();
            } finally {
                // Cerrar recursos
                try { if(rs != null) rs.close(); } catch (SQLException e) {}
                try { if(stmt != null) stmt.close(); } catch (SQLException e) {}
                try { if(conn != null) conn.close(); } catch (SQLException e) {}
            }
        } else {
            errorMessage = "Por favor ingrese ambos campos: email y contraseña.";
        }
    }
%>
<!DOCTYPE html>
<html>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 3px solid black;
            width: 350px;
            position: relative;
            margin-top: 60px;
        }

        .header {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .logo {
            width: 100px;
            height: auto;
        }
        
        .btn {
            margin: 15px 0;
            padding: 12px;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #4CAF50;
            font-weight: bold;
        }
        
        .btn:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .input-field {
            margin: 10px 0;
            padding: 12px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        
        h2 {
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }
        
        .error-message {
            color: #d9534f;
            margin: 10px 0;
            font-weight: bold;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="${pageContext.request.contextPath}/imagenes/logo.png" alt="Logo de la Empresa" class="logo">
        </div>
        
        <h2>Inicio de Sesión</h2>
        
        <%-- Mostrar mensaje de error si existe --%>
        <% if (errorMessage != null) { %>
            <div class="error-message"><%= errorMessage %></div>
        <% } %>
        
        <%-- Formulario de login --%>
        <form method="post">
            <input type="email" name="email" class="input-field" placeholder="Correo electrónico" required>
            <input type="password" name="password" class="input-field" placeholder="Contraseña" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>