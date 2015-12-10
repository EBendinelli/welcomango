<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Welcomango\Model\Booking;

class LoadBookingData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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

        $bookingManager = $this->container->get('welcomango.front.booking.manager');

        $users = $userRepo->findAll();
        $experiences = $experienceRepo->findAll();
        $status = array('Requested', 'Accepted', 'Happened', 'Refused');
        $times = $this->container->getParameter('meeting_times');

        //Generate random booking participant
        for($i=0;$i<50;$i++){
            $entry = new Booking();
            $randExperience = $experiences[array_rand($experiences)];
            $entry->setExperience($randExperience );
            $randUser = $users[array_rand($users)];
            if($randUser != $randExperience->getCreator()){
                $entry->setUser($randUser );

                $randDate = new \DateTime;
                $randTimestamp = mt_rand(1421406219,1476612219);
                $randDate->setTimestamp($randTimestamp );

                $randTime = rand(0,5);
                $requestTime = $times[$randTime];
                $bookingManager->setBookingTimeForPeriod($entry, $randDate, $requestTime);

                /*if($randExperience->isAvailableForBooking($entry->getStartTime())){*/
                $entry->setNumberOfParticipants(rand(1,10));
                $randStatus = $status[rand(0,3)];
                $entry->setStatus($randStatus);

                $manager->persist($entry);
                /*}*/
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        //Define the order in which the fixtures are executed
        return 9;
    }
}
