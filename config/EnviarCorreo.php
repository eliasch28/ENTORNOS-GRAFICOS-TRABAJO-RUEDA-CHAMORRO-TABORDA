<?php
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function plantillaCorreo(string $contenidoHtml): string
{
    return '<!DOCTYPE html>'
        . '<html lang="es"><head><meta charset="UTF-8"/>'
        . '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'
        . '<title>VuelaLibre</title></head>'
        . '<body style="margin:0;padding:24px;background:#f5f5f5;'
        . 'font-family:Arial,Helvetica,sans-serif;color:#212529;">'
        . '<div style="max-width:600px;margin:0 auto;background:#ffffff;'
        . 'border-radius:8px;padding:24px;">'
        . '<h1 style="margin:0 0 16px;font-size:20px;color:#0d6efd;">VuelaLibre</h1>'
        . $contenidoHtml
        . '<hr style="border:none;border-top:1px solid #dee2e6;margin:24px 0 12px;"/>'
        . '<p style="margin:0;font-size:12px;color:#6c757d;">'
        . 'VuelaLibre &middot; Sistema de gestión de reservas de pasajes de avión<br/>'
        . 'UTN – Facultad Regional Rosario &middot; Cátedra Entornos Gráficos 2026'
        . '</p>'
        . '</div></body></html>';
}

function correoATextoPlano(string $html): string
{
    $texto = preg_replace_callback(
        '/<a\b[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/is',
        function ($m) {
            $url      = trim($m[1]);
            $etiqueta = trim(html_entity_decode(strip_tags($m[2]), ENT_QUOTES, 'UTF-8'));
            return ($etiqueta === '' || $etiqueta === $url) ? $url : "$etiqueta ($url)";
        },
        $html
    );
    $texto = preg_replace('/<(br|\/p|\/tr|\/h[1-6]|\/div)[^>]*>/i', "\n", $texto);
    $texto = preg_replace('/<\/td>\s*<td[^>]*>/i', ': ', $texto);
    $texto = strip_tags($texto);
    $texto = html_entity_decode($texto, ENT_QUOTES, 'UTF-8');
    $texto = preg_replace('/[ \t]+/', ' ', $texto);
    $texto = preg_replace('/\n{3,}/', "\n\n", $texto);
    return trim($texto);
}

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
        if (!empty($replyTo)) {
            if (is_array($replyTo)) {
                $emailRespuesta  = $replyTo['email']  ?? '';
                $nombreRespuesta = $replyTo['nombre'] ?? '';
            } else {
                $emailRespuesta  = $replyTo;
                $nombreRespuesta = '';
            }
            if (filter_var($emailRespuesta, FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($emailRespuesta, $nombreRespuesta);
            }
        }
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = plantillaCorreo($cuerpoHtml);
        $mail->AltBody = correoATextoPlano($cuerpoHtml);
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
