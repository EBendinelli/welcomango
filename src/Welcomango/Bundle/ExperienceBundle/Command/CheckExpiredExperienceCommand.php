<?php

namespace Welcomango\Bundle\ExperienceBundle\Command;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Welcomango\Model\Availability;
use Welcomango\Model\User;
use Welcomango\Model\Experience;

/**
 * Class CheckExpiredExperienceCommand
 */
class CheckExpiredExperienceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('welcomango:experience:check-expired');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine      = $this->getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();
        $logger        = $this->getContainer()->get('logger');

        $translator = $this->getContainer()->get('translator');

        $experiencesIds = $doctrine->getRepository(Availability::class)->getExpiredExperiences();
        $experiences = $doctrine->getRepository(Experience::class)->findById($experiencesIds);

        /** @var Experience $experience */
        foreach ($experiences as $experience) {
            if($experience->getPublicationStatus() == 'published') {
                $experience->setPublicationStatus('expired');
                $entityManager->persist($experience);

                $logger->info('Experience [{id} | {title}] has been set to expired', [
                    'id' => $experience->getId(),
                    'title' => $experience->getTitle(),
                    'experience' => $experience
                ]);

                $message = \Swift_Message::newInstance()
                    ->setSubject($translator->trans('email.experience.expired.subject', array(), 'interface'))
                    ->setFrom(['no-reply@welcomango.com' => 'Welcomango Team'])
                    ->setTo($experience->getCreator()->getEmail())
                    ->setBody(
                        $this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceExpired.html.twig', array(
                                'experience' => $experience,
                                'type' => 'Text'
                            )
                        ), 'text/plain')
                    ->addPart($this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceExpired.html.twig', array('experience' => $experience)), 'text/html');

                $this->getContainer()->get('mailer')->send($message);
            }
        }

        $entityManager->flush();

        $reminderExperiencesIds = $doctrine->getRepository(Availability::class)->getAlmostExpiredExperiences();
        $reminderExperiences = $doctrine->getRepository(Experience::class)->findById($reminderExperiencesIds);
        foreach ($reminderExperiences as $experience) {
            if($experience->getPublicationStatus() == 'published') {
                $message = \Swift_Message::newInstance()
                    ->setSubject($translator->trans('email.experience.almostExpired.subject', array(), 'interface'))
                    ->setFrom(['no-reply@welcomango.com' => 'Welcomango Team'])
                    ->setTo($experience->getCreator()->getEmail())
                    ->setBody($this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceAlmostExpired.html.twig', array(
                            'experience' => $experience,
                            'type' => 'Text'
                        )
                    ), 'text/plain')
                    ->addPart($this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceAlmostExpired.html.twig', array('experience' => $experience)), 'text/html');
                $this->getContainer()->get('mailer')->send($message);
            }
        }

    }
}
