<?php

namespace Welcomango\Bundle\ExperienceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Welcomango\Model\Participation;

class LoadParticipationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
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
        $ParticipantStatus = array('booked', 'validated', 'happened');

        for($i=0;$i<10;$i++){
            $entry = new Participation();
            $entry->setUser($users[array_rand($users)]);
            $entry->setExperience($experiences[array_rand($experiences)]);
            $entry->setDate(new \DateTime());
            $entry->setStartTime(new \DateTime());
            $entry->setEndTime(new \DateTime());
            $entry->setNote(rand(1,10));

            $randomStatus = rand(0,1);
            if($randomStatus == 1){
                //Is Creator
                $entry->setIsCreator(true);
                $entry->setIsParticipant(false);
                $entry->setStatus(array_rand($creatorStatus));
            }else{
                //Is participant
                $entry->setIsCreator(false);
                $entry->setIsParticipant(true);
                $entry->setStatus(array_rand($ParticipantStatus));
            }

            $manager->persist($entry);
        }
        $manager->flush();

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