<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include "bdconexion.php";

require '../../libs/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require '../../libs/PHPMailer/PHPMailer-master/src/SMTP.php';
require '../../libs/PHPMailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validar datos
if (
    isset($_POST['carnet']) &&
    isset($_POST['password']) &&
    isset($_POST['confirm_password']) &&
    $_POST['password'] === $_POST['confirm_password']
) {
    $carnet = $conexion->real_escape_string($_POST['carnet']);
    $password = $conexion->real_escape_string($_POST['password']);

    // 1. Obtener correo del usuario
    $email = null;
    if ($stmt = $conexion->prepare("CALL selectCarnet(?, @correo, @celular)")) {
        $stmt->bind_param("s", $carnet);
        $stmt->execute();
        $stmt->close();

        $result = $conexion->query("SELECT @correo AS correo");
        $data = $result->fetch_assoc();
        $email = $data['correo'] ?? null;
    }

    if (!$email) {
        echo json_encode(["success" => false, "message" => "No se encontró el correo del usuario."]);
        exit;
    }

    // 2. Actualizar la contraseña
    if ($stmt = $conexion->prepare("CALL resetPass(?, ?)")) {
        $stmt->bind_param("ss", $password, $carnet);
        $stmt->execute();
        $stmt->close();

        // 3. Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración SMTP (ajusta según tu servidor)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'famkongt24@gmail.com';
            $mail->Password = 'mqubrjjrjpmawyuj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('famkongt24@gmail.com', 'Nombre de tu proyecto');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "
                <p>Hola,</p>
                <p>Tu contraseña ha sido actualizada correctamente.</p>
                <p>Tu nueva contraseña es: <strong>$password</strong></p>
            ";
            $mail->CharSet = 'UTF-8';
            $mail->send();

            echo json_encode(["success" => true, "message" => "Contraseña actualizada y correo enviado."]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Contraseña actualizada pero error al enviar correo: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error en el procedimiento."]);
    }

    $conexion->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Datos inválidos o contraseñas no coinciden."
    ]);
}
?>