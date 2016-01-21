<?php

namespace Welcomango\Bundle\EmailBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Twig_Environment;
use Swift_Mailer;

use Welcomango\Model\Availability;

class EmailManager
{

    protected $mailer;

    protected $twig;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    public function sendEmailAfterExperienceCreation($user){

        //Message to us so we can validate quickly
        $message = \Swift_Message::newInstance()
            ->setSubject('New experience created by')
            ->setFrom($user->getEmail())
            ->setTo('eliot@welcomango.com')
            ->setBody(
                $this->twig->render(
                    'WelcomangoEmailBundle:AdminEmailTemplate:experienceCreation.html.twig',[
                    'user' => $user,
                ]),
                'text/html'
            );
        $this->mailer->send($message);

        //send a confirmation message to the user
        $message = \Swift_Message::newInstance()
            ->setSubject('Your experience has been successfully submitted!')
            ->setFrom('no-reply@welcomango.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'WelcomangoEmailBundle:EmailTemplate:experienceCreationConfirmation.html.twig',[
                    'user' => $user
                ]),
                'text/html'
            );
        $this->mailer->send($message);

    }
}

