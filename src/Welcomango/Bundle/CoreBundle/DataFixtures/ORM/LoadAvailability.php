<?php

namespace Welcomango\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Welcomango\Model\Availability;

class LoadAvailabilityData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $experienceRepo = $manager->getRepository('Welcomango\Model\Experience');
        $availabilityManager = $this->container->get('welcomango.front.availability.manager');
        $experiences = $experienceRepo->findAll();
        $times = $this->container->getParameter('meeting_times');

        $first = true;
        foreach($experiences as $experience){
            //Each experience has a creator
            $entry = new Availability();
            $entry->setExperience($experience);

            //Let's handle the first experience differently so we have a referencial
            if($first){
                $randStartDate = new \DateTime;
                $randTimestamp = mt_rand(1421406219,1476612219);
                $randStartDate->setTimestamp($randTimestamp);
                $randStartDate->setTime($randStartDate->format('G'), 0);
                $entry->setStartDate($randStartDate);

                $randEndDate = clone $randStartDate;
                $randMonthsAvailable = rand(0,12);
                $randEndDate->add(new \DateInterval('P'.$randMonthsAvailable.'M'));
                $entry->setEndDate($randEndDate);

                //The experience is available from monday to friday in the evening and during the weekend in the morning
                $firstEntry = clone $entry;
                $firstEntry->setDay(',0,1,2,3,4,');
                $hours = $availabilityManager->generateAvailabilityHours(array('4','5'));
                $firstEntry->setHour($hours);
                $firstEntry->setMonth('*');
                $manager->persist($firstEntry);

                $secondEntry = clone $entry;
                $secondEntry->setDay(',5,6,');
                $hours = $availabilityManager->generateAvailabilityHours(array('1',''));
                $secondEntry->setHour($hours);
                $secondEntry->setMonth('*');
                $manager->persist($secondEntry);
                $first = false;
            }else {
                //Generate the Availibility entry for all the other case
                $randStartDate = new \DateTime;
                $randTimestamp = mt_rand(1421406219, 1476612219);
                $randStartDate->setTimestamp($randTimestamp);
                $randStartDate->setTime($randStartDate->format('G'), 0);
                $entry->setStartDate($randStartDate);

                $randEndDate = clone $randStartDate;
                $randMonthsAvailable = rand(1, 12);
                $randEndDate->add(new \DateInterval('P' . $randMonthsAvailable . 'M'));
                $entry->setEndDate($randEndDate);

                //Define random time of the day
                $randTime1 = rand(0, 5);
                $randTime2 = rand(0, 5);
                $randTime3 = rand(0, 5);
                $randTime4 = rand(0, 5);
                $availablePeriods = array();
                $availablePeriods[] = $times[$randTime1];
                if ($randTime2 != $randTime1) $availablePeriods[] = $times[$randTime2];
                if ($randTime3 != $randTime1 && $randTime3 != $randTime2) $availablePeriods[] = $times[$randTime3];
                if ($randTime4 != $randTime1 && $randTime4 != $randTime2 && $randTime4 != $randTime3) $availablePeriods[] = $times[$randTime4];

                $hours = $availabilityManager->generateAvailabilityHours($availablePeriods);
                $entry->setHour($hours);

                $randMonths = array();
                for ($i = 0; $i < 9; $i++) {
                    $randMonth = rand(1, 12);
                    if (!isset($randMonths[$randMonth]))
                        $randMonths[$randMonth] = $randMonth;
                }
                $randMonths = ',' . \implode(',', $randMonths) . ',';
                $entry->setMonth($randMonths);

                $randDays = array();
                for ($i = 0; $i < 5; $i++) {
                    $randDay = rand(0, 6);
                    if (!isset($randDays[$randDay]))
                        $randDays[$randDay] = $randDay;
                }
                $randDays = ',' . \implode(',', $randDays) . ',';
                $entry->setDay($randDays);

                $manager->persist($entry);
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
