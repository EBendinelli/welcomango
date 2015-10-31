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

    public function getAverageNoteForExperience($experience){
        return $this
            ->createQueryBuilder('p')
            ->select("AVG(p.note) as average_note")
            ->where('p.isParticipant = 1')
            ->andWhere('p.experience ='.$experience->getId())
            ->getQuery()
            ->getSingleResult()
            ;
    }

}