<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libs/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require '../../libs/PHPMailer/PHPMailer-master/src/SMTP.php';
require '../../libs/PHPMailer/PHPMailer-master/src/Exception.php';
require 'dbconexion.php'; // Tu conexión a la base de datos

// Recibir datos
$carnet = $_POST['carnet'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$email = $_POST['email'] ?? '';

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
    exit;
}

// Opcional: validar que el email recibido sea válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email no válido.']);
    exit;
}

// Actualizar la contraseña en la base de datos usando el SP
// IMPORTANTE: el SP espera la contraseña en texto plano, pero normalmente deberías hash la contraseña aquí
// Ejemplo usando password_hash:
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conexion->prepare("CALL resetPass(?, ?)");
$stmt->bind_param('ss', $hashedPassword, $carnet);

if ($stmt->execute()) {
    // Enviar correo con PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP - ajusta según tu servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'famkongt24@gmail.com';
        $mail->Password = 'mqubrjjrjpmawyuj';
        $mail->SMTPSecure = 'tls'; // o 'ssl'
        $mail->Port = 587; // o 465 para ssl

        $mail->setFrom('famkongt24@gmail.com', 'Soporte Proyecto');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body = "Hola,<br>Tu contraseña ha sido actualizada.<br><strong>Nueva contraseña:</strong> $password<br><br>Por favor, cambia esta contraseña después de iniciar sesión.";

        $mail->send();

        echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada y correo enviado.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error enviando correo: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error actualizando la contraseña.']);
}

$stmt->close();
$conexion->close();
?>
