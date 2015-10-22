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

}