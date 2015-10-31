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

        $users = $userRepo->findAll();
        $experiences = $experienceRepo->findAll();
        $creatorStatus = array('available', 'happened', 'booked');
        $ParticipantStatus = array('requested', 'validated', 'happened');

        //Each experience has a creator
        foreach($experiences as $experience){
            $entry = new Participation();
            $entry->setUser($users[array_rand($users)]);
            $entry->setExperience($experience);
            $randDate = new \DateTime;
            $randTimestamp = mt_rand(1421406219,1476612219);
            $randDate->setTimestamp($randTimestamp);
            $randDate->setTime($randDate->format('G'), 0);
            $entry->setDate($randDate);

            $startTime = new \Datetime;
            $startTime->setTimestamp($randTimestamp);
            $startTime->setTime(9,0);
            $entry->setStartTime($startTime);

            $endTime = new \Datetime;
            $endTime->setTimestamp($randTimestamp);
            $endTime->setTime(23,0);
            $entry->setEndTime($endTime);

            $entry->setEndTime($endTime);
            $entry->setNote(rand(1,5));

            $entry->setIsCreator(true);
            $entry->setIsParticipant(false);
            $entry->setStatus($creatorStatus[rand(0,2)]);
            $entry->setNumberOfParticipants(rand(1,10));

            $manager->persist($entry);
        }

        //Generate random participations participant
        for($i=0;$i<30;$i++){
            $entry = new Participation();
            $entry->setUser($users[array_rand($users)]);
            $entry->setExperience($experiences[array_rand($experiences)]);

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

            $entry->setNote(rand(1,5));
            $entry->setNumberOfParticipants(rand(1,10));
            $entry->setIsCreator(false);
            $entry->setIsParticipant(true);
            $entry->setStatus($ParticipantStatus[rand(0,2)]);


            $manager->persist($entry);
        }
        $manager->flush();

        //Update average notes of Experiences based on generated participations
        $experienceManager = $this->container->get('welcomango.front.experience.manager');
        foreach($experiences as $experience){
            $experienceManager->updateAverageNote($experience);
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