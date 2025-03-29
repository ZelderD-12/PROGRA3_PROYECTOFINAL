<?php
include 'bdconexion.php';
session_start();

// Procesar Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conexion->prepare("CALL BuscarUsuarios_Login(?, ?)");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $_SESSION['usuario'] = $usuario;
            
            // Guardar también en sessionStorage para JavaScript
            echo "<script>
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('usuario', JSON.stringify(".json_encode($usuario)."));
                window.location.href = '../bienvenido.html';
            </script>";
            exit();
        } else {
            header("Location: ../index.html?error=".urlencode("Credenciales incorrectas"));
            exit();
        }
        
        $stmt->close();
    } else {
        header("Location: ../index.html?error=".urlencode("Por favor complete todos los campos"));
        exit();
    }
}

// Procesar Registro
if (isset($_POST['registrar'])) {
    // ... (tu código de registro existente)
}

mysqli_close($conexion);
?>