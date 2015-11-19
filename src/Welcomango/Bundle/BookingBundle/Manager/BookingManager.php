<?php

namespace Welcomango\Bundle\BookingBundle\Manager;


class BookingManager
{
    public function __construct()
    {

    }

    //When creating an experience, process the form to generate the participations
    public function generateBookingForExperience($experience, $form){
        if($form->get('always_available')->getData()){

        }
    }

    public function processBookingQuery($booking, $form){
        $booking->setStatus('Requested');
        $date = $form->get('desired_date')->getData();

        $this->setBookingTimeForPeriod($booking, $date, $form->get('desired_time')->getData());
        if(!$booking->getExperience()->isAvailableForDate($booking->getStartTime())){
            return false;
        }
        if($booking->getExperience()->isAlreadyBookedByUser($booking)){
            return false;
        }

        return $booking;
    }

    public function setBookingTimeForPeriod($booking, $date, $time){
        $startTime = $date;
        $endTime = clone $startTime;


        switch($time){
            case '0':
            case 'Early Morning':
                $startTime->setTime(6, 0);
                $endTime->setTime(9, 0);
                break;
            case '1':
            case 'Morning':
                $startTime->setTime(9,0);
                $endTime->setTime(12, 0);
                break;
            case '2':
            case 'Lunchtime':
                $startTime->setTime(12,0);
                $endTime->setTime(15, 0);
                break;
            case '3':
            case 'Afternoon':
                $startTime->setTime(15, 0);
                $endTime->setTime(18, 0);
                break;
            case '4':
            case 'Evening':
                $startTime->setTime(18, 0);
                $endTime->setTime(21, 0);
                break;
            case '5':
            case 'Night':
                $startTime->setTime(21, 0);
                $endTime->setTime(23, 59);
                break;
        }

        $booking->setStartDatetime($startTime);
        $booking->setEndDatetime($endTime);

        return $booking;
    }

}

