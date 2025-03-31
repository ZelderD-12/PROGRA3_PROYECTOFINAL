<?php
include 'bdconexion.php';
session_start();

if (!isset($_SESSION['tipos_usuario']) || !isset($_SESSION['id_tipos_usuario'])) {
    $query = "CALL ObtenerTiposDeUsuarios();";
    $result = $conexion->query($query);
    while ($conexion->more_results()) {
        $conexion->next_result(); 
    }
    if ($result) {
        $tipos_usuario = [];
        $idtiposusuario = [];
        while ($row = $result->fetch_assoc()) {
            $tipos_usuario[] = $row['Tipo_De_Usuario'];
            $idtiposusuario[] = $row['Id_Tipo_Usuario'];
        }
        $_SESSION['tipos_usuario'] = $tipos_usuario;
        $_SESSION['id_tipos_usuario'] = $idtiposusuario;
    } else {
        echo "<script>console.log('Error en la ejecución del SP: " . $conexion->error . "');</script>";
    }
} else {
    $tipos_usuario = $_SESSION['tipos_usuario'];
    $idtiposusuario = $_SESSION['id_tipos_usuario'];
}


if (!isset($_SESSION['carreras']) || !isset($_SESSION['id_carreras'])) {
    $query = "CALL ObtenerCarreras();";
    $result = $conexion->query($query);
    while ($conexion->more_results()) {
        $conexion->next_result(); 
    }
    if ($result) {
        $carreras = [];
        $idcarreras = [];
        while ($row = $result->fetch_assoc()) {
            $carreras[] = $row['Nombre_Carrera'];
            $idcarreras[] = $row['Id_Carrera'];
        }
        $_SESSION['carreras'] = $carreras;
        $_SESSION['id_carreras'] = $idcarreras;
    } else {
        echo "<script>console.log('Error en la ejecución del SP: " . $conexion->error . "');</script>";
    }
} else {
    $carreras = $_SESSION['carreras'];
    $idcarreras = $_SESSION['id_carreras'];
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

            $_SESSION['idusuario'] = $usuario['Id_Usuario'];
            $_SESSION['carnetusuario'] = $usuario['Carnet_Usuario'];
            $_SESSION['nombresusuario'] = $usuario['Nombres_Usuario'];
            $_SESSION['apellidosusuario'] = $usuario['Apellidos_Usuario'];
            $_SESSION['contrausuario'] = $usuario['Password__Usuario'];
            $_SESSION['celusuario'] = $usuario['Numero_De_Telefono_Usuario'];
            $_SESSION['emailusuario'] = $usuario['Correo_Electronico_Usuario'];
            $_SESSION['fotousuario'] = $usuario['Foto_Usuario'];
            $_SESSION['idtipousuario'] = $usuario['Id_Tipo_Usuario'];
            $_SESSION['idcarrerausuario'] = $usuario['Id_Carrera_Usuario'];
            $_SESSION['seccionusuario'] = $usuario['Seccion_Usuario'];
            $_SESSION['usuarioactivo'] = $usuario['Activo'];
            
            // Guardar también en sessionStorage para JavaScript
            echo "<script>
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('usuario', JSON.stringify(".json_encode($usuario)."));
                window.location.href = '../Pantalla Principal/bienvenido.php';
            </script>";
            
            exit();
        } else {
            header("Location: ../../index.php?error=".urlencode("Credenciales incorrectas"));
            exit();
        }
        
        $stmt->close();
    } else {
        header("Location: ../../index.php?error=".urlencode("Por favor complete todos los campos"));
        exit();
    }
}

if (isset($_POST['registrar'])) {
    $carnet = trim($_POST['carnet']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $password = trim($_POST['password']);
    $celular = trim($_POST['celular']);
    $email = trim($_POST['email']);
    $foto = trim($_POST['foto']);
    $seccion = trim($_POST['seccion']);


    $tipouser = array_search($_POST['tipouser'], $tipos_usuario);
    $carrera = array_search($_POST['carrera'], $carreras);

    if ($tipouser === false || $carrera === false) {
        die("❌ Error: Tipo de usuario o carrera no válidos.");
    }

    $indicetipouser = $idtiposusuario[$tipouser];
    $indicecarrera = $idcarreras[$carrera];

    // Verificar que los campos no estén vacíos
    if (
        !empty($carnet) && !empty($nombres) && !empty($apellidos) && !empty($password) &&
        !empty($celular) && !empty($email) && !empty($foto) &&
        isset($indicetipouser) && isset($indicecarrera) && !empty($seccion)
    ) {
        $stmt = $conexion->prepare("CALL RegistrarUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            die("❌ Error en la preparación de la consulta: " . $conexion->error);
        }
        $actividad=1;
        $stmt->bind_param("sssssssiisi", $carnet, $nombres, $apellidos, $password, $celular, $email, $foto, $indicetipouser, $indicecarrera, $seccion, $actividad);
       
        try {
            if ($stmt->execute()) {
                echo "✅ Usuario insertado correctamente.";
            } else {
                throw new Exception("Error en la ejecución: " . $stmt->error);
            }
        } catch (Exception $e) {
            echo "❌ Error al registrar usuario: " . $stmt->error;
        }

        // Cerrar conexión
        $stmt->close();
    } else {
        echo "⚠️ Por favor, rellena todos los campos.";
    }
}

mysqli_close($conexion);
?>
