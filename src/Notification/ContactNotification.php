<?php

namespace App\Notification;

use Twig\Environment;
use App\Entity\Contact;

class ContactNotification {

    public function __construct(\Swift_Mailer $mailer, Environment $renderer){
        $this->mailer = $mailer;
        $this->render = $renderer;
    }
    public function notify(Contact $contact){
        $message = (new \Swift_Message('Agence :' . $contact->getProperty()->getTitle()))
         ->setFrom('noreply@agence.fr')
         ->setTo('contact@agence.fr')
         ->setReplyTo($contact->getEmail())
         ->setBody($this->renderer->render('emails/contact.html.twig', [
             'contact' => $contact
         ]), 'text/html');
         $this->mailer->send($message);
    }
}