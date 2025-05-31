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
            $nombreCarrera = obtenerNombreCarrera($usuario['Id_Carrera_Usuario']);
               $tipoUsuario = obtenerTipoUsuario($usuario['Id_Tipo_Usuario']);
           
            $usuario['Nombre_Carrera'] = $nombreCarrera;
            $usuario['Tipo_Usuario'] = $tipoUsuario;

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
             
             $_SESSION['nombrecarrera'] = $nombreCarrera;
            $_SESSION['tipousuario'] = $tipoUsuario;

            $pagina_destino = '../Pantalla Principal/bienvenido.php'; // Ruta base ajustada a tu estructura
            switch ($_SESSION['idtipousuario']) {
                case 1:
                    $pagina_destino = '../admin/dashboard.php';
                    break;
                case 2:
                    $pagina_destino = '../docente/panel.php';
                    break;
                case 3:
                    $pagina_destino = '../estudiante/inicio.php';
                    break;
                case 4:
                    $pagina_destino = '../Desarrolladores/panel.php';
                    break;
                case 5:
                    $pagina_destino = '../Servicios/inicio.php';
                    break;
            }

            // Guardar también en sessionStorage para JavaScript
            echo "<script>
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('usuario', JSON.stringify(" . json_encode($usuario) . "));
                window.location.href = '../Pantalla Principal/bienvenido.php';
            </script>";

            exit();
        } else {
            header("Location: ../../index.php?error=" . urlencode("Credenciales incorrectas"));
            exit();
        }

        $stmt->close();
        //bug
    } else {
        header("Location: ../../index.php?error=" . urlencode("Por favor complete todos los campos"));
        exit();
    }
}


// REGISTRAR NUEVO USUARIO (updated with new fields)
if (isset($_POST['registrar'])) {
    // Basic information
    $carnet = trim($_POST['carnet']);
    $_SESSION['carnetusuario'] = $carnet;
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $password = trim($_POST['password']);
    $celular = trim($_POST['celular']);
    $email = trim($_POST['email']);
    $seccion = trim($_POST['seccion']);
    
    // Process photo (handle both base64 and file upload)
    $foto = '';
    if (isset($_POST['foto'])) {
        $foto = trim($_POST['foto']);
        $foto = preg_replace('/^data:image\/\w+;base64,/', '', $foto);
    } elseif (isset($_FILES['foto'])) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto = base64_encode(file_get_contents($foto_tmp));
    }

    // New facial recognition fields (with null checks)
    $descriptor_facial = isset($_POST['descriptor_facial']) ? json_encode($_POST['descriptor_facial']) : NULL;
    $puntos_faciales = isset($_POST['puntos_faciales']) ? json_encode($_POST['puntos_faciales']) : NULL;
    $imagen_referencia = isset($_POST['imagen_referencia']) ? $_POST['imagen_referencia'] : NULL;
    $angulo_captura = isset($_POST['angulo_captura']) ? $_POST['angulo_captura'] : NULL;

    // Get user type and career
    $tipouser = array_search($_POST['tipouser'], $tipos_usuario);
    $carrera = array_search($_POST['carrera'], $carreras);

    if ($tipouser === false || $carrera === false) {
        die("❌ Error: Tipo de usuario o carrera no válidos.");
    }

    $indicetipouser = $idtiposusuario[$tipouser];
    $indicecarrera = $idcarreras[$carrera];

    // Validate all required fields
    if (
        !empty($carnet) && !empty($nombres) && !empty($apellidos) && !empty($password) &&
        !empty($celular) && !empty($email) && !empty($foto) &&
        isset($indicetipouser) && isset($indicecarrera) && !empty($seccion)
    ) {
        // Prepare the call to the stored procedure with all new fields
        $stmt = $conexion->prepare("CALL RegistrarUsuariosCompleto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("❌ Error en la preparación de la consulta: " . $conexion->error);
        }
        
        // Bind all parameters including the new ones
        $stmt->bind_param("sssssssiisssss", 
            $carnet, 
            $nombres, 
            $apellidos, 
            $password, 
            $celular, 
            $email, 
            $foto,
            $indicetipouser,
            $indicecarrera,
            $seccion,
            $descriptor_facial,
            $puntos_faciales,
            $imagen_referencia,
            $angulo_captura
        );

        try {
            if ($stmt->execute()) {
                echo "✅ Usuario insertado correctamente.";

                // Prepare data for PDF (including facial recognition info)
                $datosPDF = [
                    'email' => $email,
                    'fotoData' => $_POST['foto'] ?? $foto,
                    'carnet' => $carnet,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'celular' => $celular,
                    'tipo' => $_POST['tipouser'],
                    'carrera' => $_POST['carrera'],
                    'seccion' => $seccion,
                    'fecha_hora_navegador' => $_POST['fecha_hora_navegador'] ?? '',
                    'facial_registered' => ($descriptor_facial !== NULL) ? 'Sí' : 'No'
                ];

                include(__DIR__ . '/../Registro/enviar_pdf.php');

                $resultado = generarYEnviarPDF($datosPDF);
                if ($resultado === true) {
                    $_SESSION['datos_pdf'] = $datosPDF;
                    header("Location: ../Registro/Impresion.php");
                    exit();
                } else {
                    echo "Error al enviar PDF o correo: " . $resultado;
                }
            } else {
                throw new Exception("Error en la ejecución: " . $stmt->error);
            }
        } catch (Exception $e) {
            echo "❌ Error al registrar usuario: " . $e->getMessage() . " - Código de error: " . $stmt->errno;
        }

        $stmt->close();
    } else {
        echo "⚠️ Por favor, rellena todos los campos obligatorios.";
    }
}

// Función para mostrar imagen desde base64 usando el SP Obtener64 (unchanged)
function mostrarImagenDesdeSP($carnetUsuario)
{
    global $conexion;

    $conexion->query("CALL Obtener64('$carnetUsuario', @base64)");
    while ($conexion->more_results()) {
        $conexion->next_result();
    }

    $resultado = $conexion->query("SELECT @base64 AS imagen_base64");

    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $imagenBase64 = $fila['imagen_base64'];
        if ($imagenBase64) {
            echo '<img src="data:image/png;base64,' . $imagenBase64 . '" alt="Foto del usuario" />';
        } else {
            echo '📷 Imagen no encontrada.';
        }
    } else {
        echo '❌ Error al obtener la imagen desde el procedimiento almacenado.';
    }
} 

//******AQUI IMPLEMENTO LOS SP NUEVOS************************************* */
function obtenerNombreCarrera($idCarrera) {
    // Usar los datos que ya están cargados en sesión
    if (isset($_SESSION['carreras']) && isset($_SESSION['id_carreras'])) {
        $carreras = $_SESSION['carreras'];
        $idcarreras = $_SESSION['id_carreras'];
        
        // Buscar el índice del ID en el array de IDs
        $indice = array_search($idCarrera, $idcarreras);
        
        if ($indice !== false && isset($carreras[$indice])) {
            return $carreras[$indice];
        }
    }
    
    // Si no se encuentra en sesión, usar consulta directa como respaldo
    global $conexion;
    
    // Limpiar resultados pendientes
    while ($conexion->more_results()) {
        $conexion->next_result();
    }
    
    $nombreCarrera = "";
    $query = "SELECT Nombre_Carrera FROM Carreras WHERE Id_Carrera = ?";
    $stmt = $conexion->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $idCarrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $row = $result->fetch_assoc()) {
            $nombreCarrera = $row['Nombre_Carrera'];
        }
        
        $stmt->close();
    }
    
    return $nombreCarrera;
}

// Función para obtener el tipo de usuario usando los datos ya cargados
function obtenerTipoUsuario($idTipoUsuario) {
    // Usar los datos que ya están cargados en sesión
    if (isset($_SESSION['tipos_usuario']) && isset($_SESSION['id_tipos_usuario'])) {
        $tipos_usuario = $_SESSION['tipos_usuario'];
        $idtiposusuario = $_SESSION['id_tipos_usuario'];
        
        // Buscar el índice del ID en el array de IDs
        $indice = array_search($idTipoUsuario, $idtiposusuario);
        
        if ($indice !== false && isset($tipos_usuario[$indice])) {
            return $tipos_usuario[$indice];
        }
    }
    
    // Si no se encuentra en sesión, usar consulta directa como respaldo
    global $conexion;
    
    // Limpiar resultados pendientes
    while ($conexion->more_results()) {
        $conexion->next_result();
    }
    
    $tipoUsuario = "";
    $query = "SELECT Tipo_De_Usuario FROM TiposUsuario WHERE Id_Tipo_Usuario = ?";
    $stmt = $conexion->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $idTipoUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $row = $result->fetch_assoc()) {
            $tipoUsuario = $row['Tipo_De_Usuario'];
        }
        
        $stmt->close();
    }
    
    return $tipoUsuario;
}
 
?>
