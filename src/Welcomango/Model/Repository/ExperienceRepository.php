<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ExperienceRepository
 *
 */

class ExperienceRepository extends EntityRepository
{

    public function test(){
        return 'ok';
    }

    public function getFeatured($limit){
        return $this
            ->createQueryBuilder('a')
            ->where('a.featured = true')
            ->where('a.published = true')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBestRated($limit){
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.participations', 'b')
            ->where('a.published = true')
            ->groupBy('a.id')
            ->orderBy('b.note', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;

        /* REPLACE ABOVE QUERY BY THIS ONE WHEN updateAverageNote() is dev in Experience Model
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.participations', 'b')
            ->where('a.published = true')
            ->groupBy('a.id')
            ->orderBy('a.average_note', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
        */
    }

    public function findAllExperiencesCreatedByUser($user){
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.participations', 'b')
            ->where('a.published = true')
            ->andWhere('b.user ='.$user->getId())
            ->andWhere('b.isCreator = 1')
            ->groupBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllExperiencesAttendedByUser($user){
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.participations', 'b')
            ->where('a.published = true')
            ->andWhere('b.user ='.$user->getId())
            ->andWhere('b.isParticipant = 1')
            ->groupBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }
}