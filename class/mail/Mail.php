<?php

namespace mail;

use PHPMailer\PHPMailer;
use PHPMailer\Exception;

class Mail
{
    private $to;
    private $subject;
    private $message;
    private $template;
    public function __construct($to, $subject, $message, $template)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->template = $template;
    }

    public function setTemplate($template)
    {
        $this->$template = $template;
    }

    public function send()
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'mail.culture-ion.fr';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'entreprise@culture-ion.fr';                     // SMTP username
            $mail->Password   = 'rugsyakquiegep0';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('entreprise@culture-ion.fr', 'Mailer');
            $mail->addReplyTo('entreprise@culture-ion.fr', 'Forum Yonwa Cuilë');
            $mail->addAddress($this->to);

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body    = $this->compile();
            $mail->AltBody = $this->message;

            $mail->send();
        } catch (Exception $e) {
        }
    }

    public function compile()
    {
        echo $this->template;
        $message = $this->message;
        ob_start();
        /*Inclusion de la page spécifiée (qui sera mise en cache et non envoyée au navigateur*/
        include("../../res/mail/" .$this->template. ".php");
        /*Enregistrement du contenu mis en cache dans la variable $content*/
        $content = ob_get_contents();
        /*Arrêt de la mise en cache et effacement des données mises en cache*/
        ob_get_flush();

        return $content;
    }
}