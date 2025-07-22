<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';


function enviarEmail($para, $pedidoId, $total)
{
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ruankenedi01@gmail.com'; // Seu e-mail de teste
        $mail->Password = 'alnjyneueilgqslh'; // Gere uma senha de app se usar 2FA
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remetente e destinatário
        $mail->setFrom('ruankenedi01@gmail.com', 'ERP Montink');
        $mail->addAddress($para);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = "Confirmacao do Pedido #{$pedidoId}";
        $mail->Body = "Seu pedido foi realizado com sucesso!<br><strong>Valor total:</strong> R$ ".number_format($total, 2, ',', '.');

        $mail->send();
        echo 'E-mail enviado com sucesso.';
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
