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

    // IMPORTANT: Regarding notes
    // The local_note is the note given by the traveler to the local
    // The traveler_note is the note given by the local to the traveler
    // For now locals only have one experience so the experience note is based on the local note
    // Although this is already prepared to handle the case where a local have more than one experience
    // That's why the Experience model has a average_note field
    public function getAverageLocalNoteForExperience($experience){
        return $this
            ->createQueryBuilder('p')
            ->select("AVG(p.localNote) as average_note")
            ->where('p.isParticipant = 1')
            ->andWhere('p.experience ='.$experience->getId())
            ->andWhere('p.status like :status')
            ->setParameter('status', 'Happened')
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function getAverageTravelerNote($user){
        return $this
            ->createQueryBuilder('p')
            ->select("AVG(p.travelerNote) as average_note")
            ->where('p.isParticipant = 1')
            ->andWhere('p.status like :status')
            ->setParameter('status', 'Happened')
            ->andWhere('p.user ='.$user->getId())
            ->getQuery()
            ->getSingleResult()
            ;
    }

}