<?php
include 'bdconexion.php';
session_start();

if (!isset($_SESSION['tipos_usuario'])) {
    $query = "CALL ObtenerTiposDeUsuarios();";
    $result = $conexion->query($query);

    if ($result) {
        $tipos_usuario = [];
        while ($row = $result->fetch_assoc()) {
            $tipos_usuario[] = $row['Tipo_De_Usuario'];
        }
        $_SESSION['tipos_usuario'] = $tipos_usuario;
    } else {
        echo "<script>console.log('Error en la ejecución del SP: " . $conexion->error . "');</script>";
    }
} else {
    $tipos_usuario = $_SESSION['tipos_usuario'];
}


if (!isset($_SESSION['carreras'])) {
    $query = "CALL ObtenerCarreras();";
    $result = $conexion->query($query);

    if ($result) {
        $carreras = [];
        while ($row = $result->fetch_assoc()) {
            $carreras[] = $row['Nombre_Carrera'];
        }
        $_SESSION['carreras'] = $carreras;
    } else {
        echo "<script>console.log('Error en la ejecución del SP: " . $conexion->error . "');</script>";
    }
} else {
    $carreras = $_SESSION['carreras'];
}


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

if (isset($_POST['registrar'])) {
    $carnet = $_POST['carnet'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $password = $_POST['password'];
    $celular = $_POST['celular'];
    $email = $_POST['email'];
    $foto = $_POST['foto'];
    $tipouser = (int) $_POST['tipouser'];
    $carrera = (int) $_POST['carrera'];
    $seccion = $_POST['seccion'];

    // Verificar que el campo no esté vacío
    if (!empty($nombres) && !empty($apellidos) && !empty($password) && !empty($celular) && !empty($email)
        && !empty($foto) && !empty($tipouser) && !empty($carrera) && !empty($seccion) && !empty($carnet)) { 
        $user_id = 1;
        $stmt = $conexion->prepare("CALL RegistrarUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssiis", $carnet, $nombres, $apellidos, $password, $celular, $email, $foto, $tipouser, $carrera, $seccion);
        $stmt->execute();

        // Cerrar conexión
        $stmt->close();

        echo "Usuario insertado correctamente.";
    } else {
        echo "Por favor, rellenar todos los campos.";
    }
}

mysqli_close($conexion);
?>