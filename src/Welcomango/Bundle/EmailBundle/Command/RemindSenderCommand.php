<?php

namespace Welcomango\Bundle\EmailBundle\Command;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Welcomango\Model\User;

/**
 * Class RemindSenderCommand
 */
class RemindSenderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('welcomango:remind:sender')
            ->setDescription('Send email based on parameters')
            ->addArgument('user', InputArgument::OPTIONAL, 'desired user')
            ->addArgument('time', InputArgument::OPTIONAL, 'desired created');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = $input->getArgument('time');
        $specificUser = $input->getArgument('user');

        $counter             = 0;
        $mails               = array();
        $usersWithExperience = array();

        $mailer   = $this->getContainer()->get('swiftmailer.mailer');
        $doctrine = $this->getContainer()->get('doctrine');
        $logger   = $this->getContainer()->get('logger');


        if($specificUser){
            $users = $doctrine->getRepository('Model:User')->findById($specificUser);
        }else {
            if ($time) {
                $users = $doctrine->getRepository('Model:User')->findCreatedBefore($time);
            } else {
                $users = $doctrine->getRepository('Model:User')->findAll();
            }

            $experiences = $doctrine->getRepository('Model:Experience')->findAll();
            foreach ($experiences as $experience) {
                $usersWithExperience[$experience->getCreator()->getId()] = $experience->getCreator();
            }

            $users = array_diff($users, $usersWithExperience);
        }

        $progress = new ProgressBar($output, count($users));
        $translator = $this->getContainer()->get('translator');

        foreach ($users as $user) {
            $email = \Swift_Message::newInstance()
                ->setSubject($translator->trans('email.experience.creation.reminder.subject', array(), 'interface'))
                ->setFrom(['no-reply@welcomango.com' => 'Welcomango team'])
                ->setTo($user->getEmail())
                ->setBody($this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceCreationReminder.html.twig', ['user' => $user, 'type' => 'Text']), 'text/plain')
                ->addPart($this->getContainer()->get('templating')->render('WelcomangoEmailBundle:EmailTemplate:experienceCreationReminder.html.twig', ['user' => $user]), 'text/html')
            ;
            $mailer->send($email);

            $counter++;
            $progress->advance();
            $output->writeln("\n<comment>Sent to ".$user->getEmail()."</comment>");
            $mails[] = $user->getEmail();
        }

        $logger->info('Messages has been sent to {counter} Users', ['counter' => $counter, 'mails' => $mails]);
    }
}
