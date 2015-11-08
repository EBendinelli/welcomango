<?php

namespace Welcomango\Bundle\ParticipationBundle\Manager;


class ParticipationManager
{
    public function __construct()
    {

    }

    public function processParticipationQuery($participation, $form){
        $participation->setStatus('Requested');
        $participation->setIsCreator(0);
        $participation->setIsParticipant(1);
        $participation->setDate($form->get('desired_date')->getData());

        $this->updateParticipationTime($participation, $form->get('desired_time')->getData());
        if(!$participation->getExperience()->isAvailableForDate($participation->getStartTime())){
            return false;
        }
        if($participation->getExperience()->isAlreadyBookedByUser($participation)){
            return false;
        }

        return $participation;
    }

    public function updateParticipationTime($participation, $time){
        $startTime = $participation->getDate();
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

        $participation->setStartTime($startTime);
        $participation->setEndTime($endTime);

        return $participation;
    }

}

