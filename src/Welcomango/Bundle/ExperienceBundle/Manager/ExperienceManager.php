<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Welcomango\Model\Experience;

class ExperienceManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    //Update the average note based on existing participations
    public function updateAverageNote($experience){
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote = $bookingRepo->getAverageLocalNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    public function getAvailableDatesForDatePicker($experience){
        //Create an array with the forbidden dates
        $forbiddenDates = array();
        $availabilities = $experience->getAvailabilities();

        foreach($availabilities as $availability){
            $interval = \DateInterval::createFromDateString('1 day');
            $startDate = new \DateTime();
            $endDate = new \Datetime();
            $period = new \DatePeriod($startDate, $interval, $endDate->add(new \DateInterval('P1Y')));
            //First we set as forbidden the days outside of the availabilities boundaries
            foreach ($period as $day) {
                if ($day->format('Y-m-d') < $availability->getStartDate()->format('Y-m-d')
                    || $day->format('Y-m-d') > $availability->getEndDate()->format('Y-m-d')
                ) {
                    $forbiddenDates[] = $day->format('Y-m-d');
                }
            }

            //Then we take care of the days withing these boundaries
            $period = new \DatePeriod($availability->getStartDate(), $interval, $availability->getEndDate());
            foreach ($period as $day) {
                if( strrpos($availability->getDay(), ','.$day->format('w').',') === false && $availability->getDay() != "*" ){
                    $forbiddenDates[] = $day->format('Y-m-d');
                }
            }

            //Finally we remove the already booked experiences
            foreach ($period as $day) {
                foreach($experience->getBookings() as $booking){
                    if($booking->getStartDatetime()->format('Y-m-d') == $day->format('Y-m-d')){
                        $forbiddenDates[] = $day->format('Y-m-d');
                    }
                }
            }
        }




        return $forbiddenDates;
    }
}
