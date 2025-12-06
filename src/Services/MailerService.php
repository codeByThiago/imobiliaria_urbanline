<?php 

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService {
    public function sendMessage(string $enviarPara, string $assunto, string $bodyHTML, string $altBody) {
        $mail = new PHPMailer(true);
        try
        {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['GMAIL_USER'];
            $mail->Password = $_ENV['GMAIL_PASS'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            
            $mail->setFrom($_ENV['GMAIL_USER'], 'Braille3D');
            $mail->addAddress($enviarPara, 'Destinatário');

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $bodyHTML;
            $mail->AltBody = $altBody;
            // Enviar
            $mail->send();
            $_SESSION['sucess_message'] = "Mensagem enviada com sucesso, verifique o seu email!";
        }
        catch (Exception $e)
        {
            $_SESSION['error_message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        };
    }
}

?>