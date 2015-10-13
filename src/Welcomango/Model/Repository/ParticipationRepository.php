<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Welcomango\Model\Participation;

/**
 * ParticipationRepository
 *
 */

class ParticipationRepository extends EntityRepository
{

    public function test(){
        return 'ok';
    }

    public function createParticipationRequest($form){
        ldd($form->get('desired_date'));
        $form->get('desired_duration');
        $form->get('desired_time');
        $form->get('number_of_participants');

        $participation = new Participation();
        $participation->setNumberOfParticipants($form->get('number_of_participants'));

        

    }
}