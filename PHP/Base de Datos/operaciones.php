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
//REGISTRAR NUEVO USUARIO
if (isset($_POST['registrar'])) {
    $carnet = trim($_POST['carnet']);
    $_SESSION['carnetusuario'] = $carnet;
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $password = trim($_POST['password']);
    $celular = trim($_POST['celular']);
    $email = trim($_POST['email']);
    $foto = trim($_POST['foto']);
    $foto = preg_replace('/^data:image\/\w+;base64,/', '', $foto);
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
        $stmt = $conexion->prepare("CALL RegistrarUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("❌ Error en la preparación de la consulta: " . $conexion->error);
        }
        $stmt->bind_param("sssssssiis", $carnet, $nombres, $apellidos, $password, $celular, $email, $foto, $indicetipouser, $indicecarrera, $seccion);

        try {
            if ($stmt->execute()) {
                echo "✅ Usuario insertado correctamente.";

                // Preparar datos para enviar_pdf.php
                $datosPDF = [
                    'email' => $email,
                    'fotoData' => $_POST['foto'], // La foto base64 desde el formulario
                    'carnet' => $carnet,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'celular' => $celular,
                    'tipo' => $_POST['tipouser'],
                    'carrera' => $_POST['carrera'],
                    'seccion' => $seccion,
                    'fecha_hora_navegador' => $_POST['fecha_hora_navegador'] ?? ''
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

        // Cerrar conexión
        $stmt->close();
    } else {
        echo "⚠️ Por favor, rellena todos los campos.";
    }
}

// Función para mostrar imagen desde base64 usando el SP Obtener64
function mostrarImagenDesdeSP($carnetUsuario)
{
    // Requiere acceso a $conexion
    global $conexion;

    // Llamar al procedimiento almacenado con el parámetro IN y OUT
    $conexion->query("CALL Obtener64('$carnetUsuario', @base64)");
    // Procesar resultados adicionales para liberar el SP anterior
    while ($conexion->more_results()) {
        $conexion->next_result();
    }

    // Obtener el valor OUT
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

// Función para obtener todos los edificios (id y nombre)
function saberEdificios() {
    global $conexion;
    $edificios = [];

    $query = "SELECT idEdificio, Edificio FROM Edificios";
    $result = $conexion->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $edificios[] = [
                'idEdificio' => $row['idEdificio'],
                'Edificio' => $row['Edificio']
            ];
        }
        $result->free();
    }
    return $edificios;
}

// Función para obtener todos los salones (id y área)
function saberSalones() {
    global $conexion;
    $salones = [];

    $query = "SELECT idSalon, Area FROM Salones";
    $result = $conexion->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $salones[] = [
                'idSalon' => $row['idSalon'],
                'Area' => $row['Area']
            ];
        }
        $result->free();
    }
    return $salones;
}


function obtenerEstructuraSinUsuarios($idArbol) {
    global $conexion;
    
    // Limpiar resultados pendientes
    while ($conexion->more_results()) {
        $conexion->next_result();
    }
    
    $estructura = array();
    
    // Consulta para obtener todos los edificios asociados al árbol
    $queryEdificios = "SELECT DISTINCT e.idEdificio, e.Edificio 
                       FROM Busqueda b
                       JOIN Edificios e ON b.idEdificio = e.idEdificio
                       WHERE b.idBusqueda = ?";
    
    $stmtEdificios = $conexion->prepare($queryEdificios);
    
    if ($stmtEdificios) {
        $stmtEdificios->bind_param("i", $idArbol);
        $stmtEdificios->execute();
        $resultEdificios = $stmtEdificios->get_result();
        
        while ($edificio = $resultEdificios->fetch_assoc()) {
            $edificioActual = array(
                'instalacion' => $edificio['Edificio'],
                'puertas' => array()
            );
            
            // Obtener puertas del edificio
            $queryPuertas = "SELECT DISTINCT p.idPuerta, p.Puerta 
                             FROM Busqueda b
                             JOIN Puertas p ON b.idPuerta = p.idPuerta
                             WHERE b.idBusqueda = ? AND b.idEdificio = ? AND b.idPuerta IS NOT NULL";
            
            $stmtPuertas = $conexion->prepare($queryPuertas);
            if ($stmtPuertas) {
                $stmtPuertas->bind_param("ii", $idArbol, $edificio['idEdificio']);
                $stmtPuertas->execute();
                $resultPuertas = $stmtPuertas->get_result();
                
                while ($puerta = $resultPuertas->fetch_assoc()) {
                    $puertaActual = array(
                        'nombre' => $puerta['Puerta'],
                        'niveles' => array()
                    );
                    
                    // Obtener niveles de la puerta
                    $queryNiveles = "SELECT DISTINCT b.Nivel 
                                     FROM Busqueda b
                                     WHERE b.idBusqueda = ? AND b.idEdificio = ? AND b.idPuerta = ? AND b.Nivel IS NOT NULL
                                     ORDER BY b.Nivel";
                    
                    $stmtNiveles = $conexion->prepare($queryNiveles);
                    if ($stmtNiveles) {
                        $stmtNiveles->bind_param("iii", $idArbol, $edificio['idEdificio'], $puerta['idPuerta']);
                        $stmtNiveles->execute();
                        $resultNiveles = $stmtNiveles->get_result();
                        
                        while ($nivel = $resultNiveles->fetch_assoc()) {
                            $nivelActual = array(
                                'numero' => $nivel['Nivel'],
                                'salones' => array()
                            );
                            
                            // Obtener salones del nivel
                            $querySalones = "SELECT DISTINCT s.idSalon, s.Area 
                                            FROM Busqueda b
                                            JOIN Salones s ON b.idSalon = s.idSalon
                                            WHERE b.idBusqueda = ? AND b.idEdificio = ? AND b.idPuerta = ? AND b.Nivel = ? AND b.idSalon IS NOT NULL";
                            
                            $stmtSalones = $conexion->prepare($querySalones);
                            if ($stmtSalones) {
                                $stmtSalones->bind_param("iiii", $idArbol, $edificio['idEdificio'], $puerta['idPuerta'], $nivel['Nivel']);
                                $stmtSalones->execute();
                                $resultSalones = $stmtSalones->get_result();
                                
                                while ($salon = $resultSalones->fetch_assoc()) {
                                    $salonActual = array(
                                        'numero' => $salon['Area']
                                    );
                                    
                                    $nivelActual['salones'][] = $salonActual;
                                }
                                $stmtSalones->close();
                            }
                            
                            $puertaActual['niveles'][] = $nivelActual;
                        }
                        $stmtNiveles->close();
                    }
                    
                    $edificioActual['puertas'][] = $puertaActual;
                }
                $stmtPuertas->close();
            }
            
            $estructura[] = $edificioActual;
        }
        $stmtEdificios->close();
    }
    
    return $estructura;
}
?>