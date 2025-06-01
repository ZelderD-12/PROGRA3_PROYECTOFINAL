<?php
require_once '../../libs/TCPDF/TCPDF-main/tcpdf.php';
require '../../libs/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require '../../libs/PHPMailer/PHPMailer-master/src/SMTP.php';
require '../../libs/PHPMailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Clase para pie de página
class PDFConPie extends TCPDF
{
    public $fechaHoraPie = '';

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Fecha y Hora Emitida del PDF: ' . $this->fechaHoraPie, 0, false, 'C');
    }
}

function generarYEnviarPDF($datos)
{
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

        $pdf = new PDFConPie();
        $pdf->fechaHoraPie = htmlspecialchars($fecha_hora_navegador);
        $pdf->AddPage();

        // Rutas a los logos
        $logoIzquierdo = __DIR__ . '/../../imagenes/logoumg.png';
        $logoDerecho = __DIR__ . '/../../imagenes/logo.png';

        // Inserta los logos en las esquinas
        // Fondo para logo izquierdo
        if (file_exists($logoIzquierdo)) {
            $pdf->SetAlpha(0.5); // Fondo con opacidad del 50%
            $pdf->SetFillColor(0, 204, 204);
            $pdf->Rect(8, 8, 34, 34, 'F');
            $pdf->SetAlpha(1); // Restaura opacidad al 100%

            $pdf->Image($logoIzquierdo, 10, 10, 30); // Logo normal
        }


        // Fondo para logo derecho
        if (file_exists($logoDerecho)) {
            $pdf->SetAlpha(0.5); // Fondo con opacidad del 50%
            $pdf->SetFillColor(0, 255, 255);
            $pdf->Rect(168, 8, 34, 34, 'F');
            $pdf->SetAlpha(1); // Restaura opacidad al 100%

            $pdf->Image($logoDerecho, 170, 10, 30); // Logo normal
        }




        // Título centrado
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Ln(15);
        $pdf->Cell(0, 15, 'REGISTRO DE USUARIO', 0, 1, 'C');

        // Línea divisoria
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(10);

        // Define estilos de la tabla
        $html = '
<style>
    th {
        background-color: #001f3f;
        color: #ffffff;
        font-weight: bold;
        padding: 5px;
        border: 1px solid #000000;
    }
    td {
        border: 1px solid #000000;
        padding: 5px;
    }
</style>

<table cellpadding="4">
    <tr>
        <th>Carnet</th>
        <td>' . htmlspecialchars($carnet) . '</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>' . htmlspecialchars($nombres) . '</td>
    </tr>
    <tr>
        <th>Apellido</th>
        <td>' . htmlspecialchars($apellidos) . '</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>' . htmlspecialchars($email) . '</td>
    </tr>
    <tr>
        <th>Celular</th>
        <td>' . htmlspecialchars($celular) . '</td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td>' . htmlspecialchars($tipo) . '</td>
    </tr>
    <tr>
        <th>Carrera</th>
        <td>' . htmlspecialchars($carrera) . '</td>
    </tr>
    <tr>
        <th>Sección</th>
        <td>' . htmlspecialchars($seccion) . '</td>
    </tr>
    <tr>
        <th>Foto</th>
        <td>            
            <img src="' . $fotoPath . '" alt="Foto Usuario" style="width:auto; height:auto;"/>
        </td>
    </tr>
</table>
';

        $pdf->writeHTML($html, true, false, false, false, '');

        // Guarda el PDF
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
        $mail->CharSet = 'UTF-8';
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
