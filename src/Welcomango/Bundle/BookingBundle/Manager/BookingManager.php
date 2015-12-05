<?php

namespace Welcomango\Bundle\BookingBundle\Manager;

use Doctrine\ORM\EntityManager;

class BookingManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //When creating an experience, process the form to generate the participations
    public function generateBookingForExperience($experience, $form){
        if($form->get('always_available')->getData()){

        }
    }

    public function processBookingQuery($booking, $form){
        $booking->setStatus('Requested');
        $date = $form->get('desired_date')->getData();

        $this->setBookingTimeForPeriod($booking, $date, $form->get('desired_time')->getData(), $form->get('desired_duration')->getData());
        if(!$booking->getExperience()->isAvailableForBooking($booking)){
            return false;
        }
        if($booking->getExperience()->isAlreadyRequestedByUser($booking)){
            return false;
        }

        return $booking;
    }

    public function setBookingTimeForPeriod($booking, $date, $time, $duration = 3){
        $startTime = $date;
        $endTime = clone $startTime;

        switch($time){
            case '0':
            case 'Early Morning':
                $startTime->setTime(6, 0);
                $endTime->setTime(6+$duration, 0);
                break;
            case '1':
            case 'Morning':
                $startTime->setTime(9,0);
                $endTime->setTime(9+$duration, 0);
                break;
            case '2':
            case 'Lunchtime':
                $startTime->setTime(12,0);
                $endTime->setTime(12+$duration, 0);
                break;
            case '3':
            case 'Afternoon':
                $startTime->setTime(15, 0);
                $endTime->setTime(15+$duration, 0);
                break;
            case '4':
            case 'Evening':
                $startTime->setTime(18, 0);
                if(18+$duration>23){
                    $endTime->setTime(23, 0);
                } else{
                    $endTime->setTime(18+$duration, 0);
                }
                break;
            case '5':
            case 'Night':
                $startTime->setTime(21, 0);
                if(21+$duration>23){
                    $endTime->setTime(23, 0);
                } else{
                    $endTime->setTime(21+$duration, 0);
                }
                break;
        }

        $booking->setStartDatetime($startTime);
        $booking->setEndDatetime($endTime);

        return $booking;
    }

    public function updateBookingStatus($experience){
        $bookings = $experience->getBookings();
        $today = new \Datetime();
        foreach($bookings as $booking){
            if($booking->getStatus() == 'Accepted' && $booking->getStartDatetime() < $today){
                $booking->setStatus('Happened');
                $booking->setActionRequired(true);
                $this->entityManager->persist($booking);
            }
        }
        $this->entityManager->flush();
    }

    public function updateNote($booking, $user, $note){
        if($user == $booking->getUser()){
            $booking->setLocalNote($note+1);
        }else{
            $booking->setTravelerNote($note+1);
        }
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
    }

}

