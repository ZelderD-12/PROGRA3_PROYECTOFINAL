<?php
include 'bdconexion.php'; // Incluir el archivo de conexión

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

// Cerrar conexión
mysqli_close($conexion);
?>
