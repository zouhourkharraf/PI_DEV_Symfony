<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendEmail($destinataire, $objet, $contenu_html): void
    {
        $email = (new Email())
            ->from('magicbook835@gmail.com')
            ->to($destinataire)
            ->subject($objet)
            ->html($contenu_html);

        $this->mailer->send($email);
    }
}


/*
Une copie de la structure complète pour un test:
    public function sendEmail($destinataire, $objet, $contenu_html): void
    {
        $email = (new Email())
            ->from('magicbook835@gmail.com')
            ->to($destinataire)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($objet)
            //->text('Sending emails is fun again!')
            ->html($contenu_html);

        $this->$mailer->send($email);
    }


*/