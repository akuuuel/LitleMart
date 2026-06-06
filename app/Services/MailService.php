<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    /**
     * Send email using PHPMailer and settings from .env
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body HTML body
     * @param string $altBody Plain text body (optional)
     * @return bool
     */
    public static function send($to, $subject, $body, $altBody = '') {
        // Fallback to Log if mailer is set to 'log' or credentials missing in local env
        if (($_ENV['MAIL_MAILER'] ?? 'smtp') === 'log') {
            $logDir = __DIR__ . '/../../logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
            $logFile = $logDir . '/mail.log';
            $content = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject\r\nBODY: " . strip_tags($body) . "\r\n" . str_repeat('-', 50) . "\r\n";
            @file_put_contents($logFile, $content, FILE_APPEND);
            return true;
        }

        $mail = new PHPMailer(true);

        try {
            // Server settings from .env
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io';
            $mail->SMTPAuth   = ($_ENV['MAIL_USERNAME'] && $_ENV['MAIL_USERNAME'] !== 'null');
            $mail->Username   = $_ENV['MAIL_USERNAME'] === 'null' ? '' : ($_ENV['MAIL_USERNAME'] ?? '');
            $mail->Password   = $_ENV['MAIL_PASSWORD'] === 'null' ? '' : ($_ENV['MAIL_PASSWORD'] ?? '');
            
            $encryption = $_ENV['MAIL_ENCRYPTION'] ?? 'null';
            if ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = '';
            }
            
            $mail->Port = $_ENV['MAIL_PORT'] ?? 2525;

            // Recipients
            $fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@litlemart.com';
            $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'LitleMart';
            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Send password reset email
     */
    public static function sendPasswordReset($to, $name, $resetLink) {
        $subject = "Atur Ulang Kata Sandi LitleMart";
        
        $body = "
            <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded: 12px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #056526;'>LitleMart</h1>
                </div>
                <h2 style='color: #1a202c;'>Halo, $name!</h2>
                <p style='color: #4a5568; line-height: 1.6;'>
                    Kami menerima permintaan untuk mengatur ulang kata sandi akun LitleMart Anda. 
                    Klik tombol di bawah ini untuk melanjutkan:
                </p>
                <div style='text-align: center; margin: 40px 0;'>
                    <a href='$resetLink' style='background-color: #056526; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>
                        Atur Ulang Kata Sandi
                    </a>
                </div>
                <p style='color: #718096; font-size: 14px;'>
                    Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini. 
                    Link ini akan kedaluwarsa dalam beberapa waktu ke depan.
                </p>
                <hr style='border: 0; border-top: 1px solid #edf2f7; margin: 40px 0;'>
                <p style='color: #a0aec0; font-size: 12px; text-align: center;'>
                    &copy; " . date('Y') . " LitleMart Ecosystem. All rights reserved.
                </p>
            </div>
        ";

        return self::send($to, $subject, $body);
    }
}
