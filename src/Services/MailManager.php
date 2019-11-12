<?php 

namespace App\Services; 

use App\Entity\Contact; 

class MailManager
{
    private $mailer; 
    private $twig; 

    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $twig)
    {
        $this->mailer = $mailer; 
        $this->twig = $twig; 
    }
    public function sendMailToDepartment(Contact $contact)
    {
        $message = new \Swift_Message('Contact');
        $message
            ->setFrom('hello@remi-ponche.fr', 'Efficience IT')
            ->setTo($contact->getDepartment()->getMail())
            ->setBody(
                $this->twig->render(
                    'mail/contact.txt.twig',
                    [
                        'contact' => $contact,
                    ]
                ),
                'text/plain'
            );

        $this->mailer->send($message);
    }
}