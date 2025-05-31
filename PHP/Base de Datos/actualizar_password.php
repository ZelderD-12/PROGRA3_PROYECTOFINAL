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
    $email = isset($_POST['email']) ? $conexion->real_escape_string($_POST['email']) : '';

    // Llama al SP para actualizar la contraseña
    if ($stmt = $conexion->prepare("CALL resetPass(?, ?)")) {
        $stmt->bind_param("ss", $password, $carnet);
        $stmt->execute();
        $stmt->close();

        // Enviar correo
        if (!empty($email)) {
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'famkongt24@gmail.com';
                $mail->Password = 'mqubrjjrjpmawyuj'; // contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Emisor y receptor
                $mail->setFrom('famkongt24@gmail.com', 'Soporte');
                $mail->addAddress($email);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Contraseña actualizada';
                $mail->Body = "<p>Hola,</p><p>Tu contraseña ha sido actualizada exitosamente.</p><p>Si no fuiste tú, comunícate con el soporte.</p>";
                $mail->CharSet = 'UTF-8';
                $mail->send();
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Contraseña actualizada, pero no se pudo enviar el correo: " . $mail->ErrorInfo]);
                exit;
            }
        }

        echo json_encode(["success" => true, "message" => "Contraseña actualizada con éxito. Se envió un correo."]);
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
