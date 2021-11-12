<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService 
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token) 
    {
        $email = (new TemplatedEmail())
        ->from('sanglierouge@gmail.com')
        ->to(new Address($email))
        ->subject('Thanks for signing up!')
    
        // path of the Twig template to render
        ->htmlTemplate('emails/signup.html.twig')
    
        // pass variables (name => value) to the template
        ->context([
      
            'token' => $token,
        ])
    ;

        $this->mailer->send($email);
    }


}