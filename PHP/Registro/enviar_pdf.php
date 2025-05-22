<?php
require_once '../../libs/TCPDF/TCPDF-main/tcpdf.php';
require '../../libs/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require '../../libs/PHPMailer/PHPMailer-master/src/SMTP.php';
require '../../libs/PHPMailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generarYEnviarPDF($datos) {
    try {
        extract($datos); // Extrae variables del arreglo
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Correo inválido');
        if (empty($fotoData)) throw new Exception('Foto no recibida');
        
        $tempFolder = __DIR__ . '/temp/';
        if (!file_exists($tempFolder)) mkdir($tempFolder, 0777, true);
        
        $fotoPath = $tempFolder . $carnet . '_' . uniqid() . '.jpg';
        $pdfFilePath = $tempFolder . 'Registro_' . $carnet . '_' . uniqid() . '.pdf';

        $foto = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoData));
        file_put_contents($fotoPath, $foto);

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, "Registro de Usuario", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, "Carnet: $carnet\nNombre: $nombres\nApellido: $apellidos\nEmail: $email\nCelular: $celular\nTipo: $tipo\nCarrera: $carrera\nSección: $seccion", 0, 'L');
        $pdf->Image($fotoPath, 15, 90, 50, 50);
        $pdf->Output($pdfFilePath, 'F');

        //Conexión al servicio de GMAIL
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'famkongt24@gmail.com';
        $mail->Password = 'mqubrjjrjpmawyuj'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('famkongt24@gmail.com', 'Sistema de Registro');
        $mail->addAddress($email, "$nombres $apellidos");
        $mail->isHTML(true);
        $mail->Subject = 'Registro completado';
        $mail->Body = "
            <p>Hola $nombres,</p>
            <p>Tu registro fue exitoso. Adjuntamos un PDF con tus datos.</p>
            <p>Saludos,<br>Equipo de Registro</p>
        ";
        $mail->addAttachment($pdfFilePath);

        $mail->send();

        // Limpiar archivos temporales
        unlink($fotoPath);
        unlink($pdfFilePath);

        return true;

    } catch (Exception $e) {
        error_log("Error en generarYEnviarPDF: " . $e->getMessage());
        return $e->getMessage();
    }
}
?>
