<?php

namespace App\Jobs; 

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;


class SendEmailVerification {

    private $email = '';
    private $name = '';
    private $verification_code = '';

    private $host_work_mail = 'mail.stalinks.com';
    private $work_mail = 'richard@stalinks.com';
    private $security_work_mail = 'tls';
    private $work_mail_pass = 'ox8GFfylVAdR';
    private $port_work_mail = '587';

    public function __construct( $email, $name, $verification_code)
    {
        $this->name = $name;
        $this->email = $email;
        $this->verification_code = $verification_code;
    }

    public function sendEmail()
    {
        try {
            $transport = (new Swift_SmtpTransport($this->host_work_mail, $this->port_work_mail, $this->security_work_mail))
                ->setUsername($this->work_mail)
                ->setPassword($this->work_mail_pass)
                ->setStreamOptions(array(
                    'ssl' => array(
                        'allow_self_signed' => true,
                        'verify_peer' => false)));

            $mailer = new Swift_Mailer($transport);

            $data = [
                'name' => $this->name,
                'verification_code' => $this->verification_code
            ];

            $message = (new Swift_Message('Email Verification'))
                ->setFrom([$this->work_mail => 'Stalinks System'])
                ->setTo([$this->email])
                ->setBody(view('email', $data)->render(),'text/html');

            $result = $mailer->send($message);

            return $result;
  
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}