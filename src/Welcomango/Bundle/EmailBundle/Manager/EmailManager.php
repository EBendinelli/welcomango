<?php

namespace Welcomango\Bundle\EmailBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Twig_Environment;
use Swift_Mailer;
use Symfony\Component\Translation\TranslatorInterface;

use Welcomango\Model\Availability;
use Welcomango\Model\Experience;

class EmailManager
{

    protected $mailer;

    protected $twig;

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    /**
     * @var Experience $experience
     */
    public function sendEmailAfterExperienceCreation($experience){

        $user= $experience->getCreator();
        $status = 'created';
        if($experience->getCreatedAt() != $experience->getUpdatedAt ()){
            $status = 'edited';
        }

        //Message to us so we can validate quickly
        $message = \Swift_Message::newInstance()
            ->setSubject('New experience '.$status.' by '.$user)
            ->setFrom(['moderation@welcomango.com' => 'Experience moderation'])
            ->setTo('eliot@welcomango.com')
            ->setBody(
                $this->twig->render(
                    'WelcomangoEmailBundle:AdminEmailTemplate:experienceCreation.html.twig',[
                    'experience' => $experience,
                    'user' => $user,
                    'status' => $status,
                ]),
                'text/html'
            );
        $this->mailer->send($message);

        //send a confirmation message to the user
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('email.experience.creation.subject',array(), 'interface'))
            ->setFrom(['no-reply@welcomango.com' => 'Welcomango Team'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'WelcomangoEmailBundle:EmailTemplate:experienceCreationConfirmation.html.twig',[
                    'experience' => $experience
                ]),
                'text/html'
            );

        $this->mailer->send($message);

    }
}

