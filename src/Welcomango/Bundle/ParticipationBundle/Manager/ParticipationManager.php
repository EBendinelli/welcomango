<?php

namespace Welcomango\Bundle\ParticipationBundle\Manager;


class ParticipationManager
{
    public function __construct()
    {

    }

    public function processParticipationQuery($participation, $form){
        $participation->setStatus('requested');
        $participation->setIsCreator(0);
        $participation->setIsParticipant(1);
        $participation->setDate($form->get('desired_date')->getData());

        $date = $form->get('desired_date')->getData();
        $startDate = $date;
        $endDate = $startDate;
        if($form->get('desired_time')->getData() == 0) {
            $startDate->setTime(9, 0);
            $endDate = $endDate->setTime(9 + $form->get('desired_duration')->getData(),0);
        }elseif($form->get('desired_time')->getData() == 1){
            $startDate->setTime(12,0);
            $endDate = $endDate->setTime(12 + $form->get('desired_duration')->getData(),0);
        }elseif($form->get('desired_time')->getData() == 2){
            $startDate->setTime(15,0);
            $endDate = $endDate->setTime(15 + $form->get('desired_duration')->getData(),0);
        }elseif($form->get('desired_time')->getData() == 3){
            $startDate->setTime(19, 0);
            $endDate = $endDate->setTime(19 + $form->get('desired_duration')->getData(),0);
        }

        $participation->setStartTime($startDate);
        $participation->setendTime($endDate);

        return $participation;
    }

}
