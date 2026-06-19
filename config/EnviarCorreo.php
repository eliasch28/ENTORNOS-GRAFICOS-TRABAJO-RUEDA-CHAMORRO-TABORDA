<?php
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function enviarCorreo($destinatario, $asunto, $cuerpoHtml, $debug = false, $replyTo = null)
{
    $mail = new PHPMailer(true);
    try {
        if ($debug) {
            $mail->SMTPDebug   = 2;
            $mail->Debugoutput = function ($str, $level) {
                error_log("PHPMailer debug: $str");
                echo htmlspecialchars($str) . "<br>\n";
            };
        }
        $mail->isSMTP();
        $mail->Host       = MAIL_SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_SMTP_USER;
        $mail->Password   = MAIL_SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_SMTP_PORT;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 15;
        $mail->setFrom(MAIL_SMTP_USER, MAIL_FROM_NAME);
        $mail->addAddress($destinatario);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpoHtml;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Error al enviar correo a ' . $destinatario . ': ' . $mail->ErrorInfo);
        if ($debug) {
            echo '<strong>ErrorInfo:</strong> ' . htmlspecialchars($mail->ErrorInfo) . "<br>\n";
        }
        return false;
    }
}