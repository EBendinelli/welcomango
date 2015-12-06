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
            ->addArgument('time', InputArgument::REQUIRED, 'desired created');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = $input->getArgument('time');

        $counter             = 0;
        $mails               = array();
        $usersWithExperience = array();

        $mailer   = $this->getContainer()->get('swiftmailer.mailer');
        $doctrine = $this->getContainer()->get('doctrine');
        $logger   = $this->getContainer()->get('logger');

        $users       = $doctrine->getRepository('Model:User')->findCreatedBefore($time);
        $experiences = $doctrine->getRepository('Model:Experience')->findAll();

        foreach ($experiences as $experience) {
            $usersWithExperience[] = $experience->getCreator();
        }

        $users    = array_diff($users, $usersWithExperience);
        $progress = new ProgressBar($output, count($users));


        foreach ($users as $user) {
            $output->writeln("<comment>Sent to ".$user->getEmail()."</comment>");
            $counter++;
            $progress->advance();
            $mails[] = $user->getEmail();
        }

        $logger->info('Messages has been sent to {counter} Users', ['counter' => $counter, 'mails' => $mails]);
    }
}
