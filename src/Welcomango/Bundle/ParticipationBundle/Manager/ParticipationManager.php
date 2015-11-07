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

        return $participation;
    }

    public function updateParticipationTime($participation, $time){
        $startTime = $participation->getDate();
        $endTime = $startTime;

        if($time == 'Early Morning') {
            $startTime->setTime(6, 0);
            $endTime->setTime(9, 0);
        }elseif($time == 'Morning'){
            $startTime->setTime(9,0);
            $endTime->setTime(12, 0);
        }elseif($time == 'Lunchtime'){
            $startTime->setTime(12,0);
            $endTime->setTime(15, 0);
        }elseif($time == 'Afternoon'){
            $startTime->setTime(15, 0);
            $endTime->setTime(19, 0);
        }elseif($time == 'Evening'){
            $startTime->setTime(19, 0);
            $endTime->setTime(22, 0);
        }elseif($time == 'Night'){
            $startTime->setTime(22, 0);
            $endTime->setTime(23, 59);
        }

        $participation->setStartTime($startTime);
        $participation->setendTime($endTime);

    }

}

