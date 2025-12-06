<?php 

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService {
    public function sendMessage(string $assunto, string $corpo, string $altCorpo, string $to) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = 'true';
            $mail->Username = $_ENV[''];
            $mail->Password = $_ENV[''];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->setFrom($_ENV['GMAIL_USER'], 'Urbanline Imóveis');
            $mail->addAddress($to, 'Destinatário');

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body =    $corpo;
            $mail->AltBody = $altCorpo;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Problema");
        }
    }
}

?>