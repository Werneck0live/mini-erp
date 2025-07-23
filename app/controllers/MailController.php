<?php

require_once '../libraries/PHPMailer/PHPMailer.php';
require_once '../libraries/PHPMailer/SMTP.php';
require_once '../libraries/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController {

    private $config;

    public function __construct()
    {
        $this->config = include '../config/mail.php';
    }
    
    public function sendMail($contaDestino, $assuntoEmail, $corpoEmail){
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->CharSet    = $this->config['smtp_charset'];
            $mail->Host       = $this->config['smtp_host'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->SMTPAuth =  !empty($this->config['smtp_auth']);
            $mail->Username   = $this->config['smtp_username'];
            $mail->Password   = $this->config['smtp_password'];
            
            if (!empty($this->config['smtp_secure'])) {
                $mail->SMTPSecure = $this->config['smtp_secure'];
            }

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($contaDestino);
            $mail->Subject = $assuntoEmail;
            $mail->Body    = $corpoEmail;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception($mail->ErrorInfo);
        }
    }
}
?>