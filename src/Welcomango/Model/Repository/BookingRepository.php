<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Welcomango\Model\Participation;

/**
 * BookingRepository
 *
 */

class BookingRepository extends EntityRepository
{

    // IMPORTANT: Regarding notes
    // The local_note is the note given by the traveler to the local
    // The traveler_note is the note given by the local to the traveler
    // For now locals only have one experience so the experience note is based on the local note
    // Although this is already prepared to handle the case where a local have more than one experience
    // That's why the Experience model has a average_note field
    public function getAverageNoteForExperience($experience){
        $result = $this
            ->createQueryBuilder('b')
            ->leftJoin('b.feedbacks', 'f')
            ->select("AVG(f.note) as average_note")
            ->andWhere('b.experience ='.$experience->getId())
            ->andWhere('b.status like :status')
            ->setParameter('status', 'Happened')
            ->andWhere('f.validated = 1')
            ->getQuery()
            ->getSingleResult()
            ;

        return $result;
    }

    public function getAverageNoteForUser($user){
        return $this
            ->createQueryBuilder('b')
            ->leftJoin('b.feedbacks', 'f')
            ->select("AVG(f.note) as average_note")
            ->andWhere('b.status like :status')
            ->setParameter('status', 'Happened')
            ->andWhere('b.user ='.$user->getId())
            ->andWhere('f.validated = 1')
            ->getQuery()
            ->getSingleResult()
            ;
    }
}