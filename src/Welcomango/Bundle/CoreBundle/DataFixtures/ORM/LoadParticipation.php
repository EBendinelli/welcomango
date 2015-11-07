<?php

namespace Welcomango\Bundle\ExperienceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Welcomango\Model\Participation;

class LoadParticipationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userRepo = $manager->getRepository('Welcomango\Model\User');
        $experienceRepo = $manager->getRepository('Welcomango\Model\Experience');

        $participationManager = $this->container->get('welcomango.front.experience.manager');

        $users = $userRepo->findAll();
        $experiences = $experienceRepo->findAll();
        $creatorStatus = array('Available', 'Happened', 'Booked');
        $ParticipantStatus = array('Requested', 'Accepted', 'Happened');
        $times = $this->container->getParameter('meeting_times');

        //Each experience has a creator
        foreach($experiences as $experience){
            for($i=0;$i<40;$i++){
                $entry = new Participation();
                $entry->setUser($users[array_rand($users)]);
                $entry->setExperience($experience);

                //Get random date
                $randDate = new \DateTime;
                $randTimestamp = mt_rand(1421406219,1476612219);
                $randDate->setTimestamp($randTimestamp);
                $randDate->setTime($randDate->format('G'), 0);
                $entry->setDate($randDate);

                //Define random time of the day
                $randTime1 = rand(0,5);
                $randTime2 = rand(0,5);
                $randTime3 = rand(0,5);
                $randTime4 = rand(0,5);
                $availableTimes = array();
                $availableTimes[] = $times[$randTime1];
                if($randTime2 != $randTime1) $availableTimes[] = $times[$randTime2];
                if($randTime3 != $randTime1 && $randTime3 != $randTime2) $availableTimes[] = $times[$randTime3];
                if($randTime4 != $randTime1 && $randTime4 != $randTime2 && $randTime4 != $randTime3 ) $availableTimes[] = $times[$randTime4];

                $participationManager->

                $startTime = new \Datetime;
                $startTime->setTimestamp($randTimestamp);
                $startTime->setTime(9,0);
                $entry->setStartTime($startTime);

                $endTime = new \Datetime;
                $endTime->setTimestamp($randTimestamp);
                $endTime->setTime(23,0);
                $entry->setEndTime($endTime);

                $entry->setIsCreator(true);
                $entry->setIsParticipant(false);
                $entry->setStatus($creatorStatus[rand(0,2)]);
                $entry->setNumberOfParticipants(rand(1,10));

                $manager->persist($entry);
            }
        }

        //Generate random participations participant
        for($i=0;$i<40;$i++){
            $entry = new Participation();
            $randExperience = $experiences[array_rand($experiences)];
            $entry->setExperience($randExperience );
            $randUser = $users[array_rand($users)];
            if($randUser != $randExperience->getAuthor()){
                $entry->setUser($randUser );

                $randDate = new \DateTime;
                $randTimestamp = mt_rand(1421406219,1476612219);
                $randDate->setTimestamp($randTimestamp );
                $entry->setDate($randDate);

                $startTime = new \Datetime;
                $startTime->setTimestamp($randTimestamp);
                $randTime = rand(9, 19);
                $startTime->setTime($randTime ,0);
                $entry->setStartTime($startTime);

                $endTime = new \Datetime;
                $endTime->setTimestamp($randTimestamp);
                $endTime->setTime($randTime+rand(1,5),0);
                $entry->setEndTime($endTime);

                $entry->setLocalNote(rand(1,5));
                $entry->setTravelerNote(rand(1,5));
                $entry->setNumberOfParticipants(rand(1,10));
                $entry->setIsCreator(false);
                $entry->setIsParticipant(true);
                $entry->setStatus($ParticipantStatus[rand(0,2)]);


                $manager->persist($entry);
            }
        }
        $manager->flush();

        //Update average notes of Experiences based on generated participations
        $experienceManager = $this->container->get('welcomango.front.experience.manager');
        foreach($experiences as $experience){
            $experienceManager->updateAverageNote($experience);
        }

        $userManager = $this->container->get('welcomango.front.user.manager');
        foreach($users as $user){
            $userManager->updateAverageTravelerNote($user);
            $userManager->updateAverageLocalNote($user);
        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        //Define the order in which the fixtures are executed
        return 7;
    }
}