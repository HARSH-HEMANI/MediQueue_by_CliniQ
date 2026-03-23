<?php
// ============================================================
// mailer.php — MediQueue by CliniQ
// Sends HTML emails using PHPMailer + Gmail SMTP
// Usage: include 'mailer.php'; sendEmail($to, $subject, $body);
// ============================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require('PHPMailer/PHPMailer.php');
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');

function sendEmail($to, $subject, $body, $file = null)
{
    $mail = new PHPMailer(true);

    try {
        // ---- SMTP Configuration ----
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 465;
        $mail->Username   = 'indextrader82@gmail.com';   // <-- Your Gmail
        $mail->Password   = 'vzqd dhns eacx uoaf';       // <-- Your Gmail App Password

        // Disable SSL verification for local/dev
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        // ---- Sender & Recipient ----
        $mail->setFrom('indextrader82@gmail.com', 'MediQueue by CliniQ');
        $mail->addReplyTo('indextrader82@gmail.com', 'MediQueue Support');
        $mail->addAddress($to);

        // ---- Content ----
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        // ---- Optional Attachment ----
        if ($file && file_exists($file)) {
            $mail->addAttachment($file);
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Email failed: ' . $mail->ErrorInfo;
    }
}
