<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Collections\ArrayCollection;
use Proxies\__CG__\Welcomango\Model\Availability;

/**
 * AvailabilityRepository
 *
 */

class AvailabilityRepository extends EntityRepository
{

    /**
     * @return array
     */
    public function getExpiredExperiences()
    {

        $queryBuilder = $this
            ->createQueryBuilder('a')
            ->select('(a.experience) as id')
            ->addSelect('MAX(a.endDate) as maxEndDate')
            ->groupBy('a.experience')
            ->andHaving('maxEndDate <= :end_date')
            ->setParameter('end_date', date('Y-m-d').'%')
            ->getQuery();

        $results = $queryBuilder->getArrayResult();
        $experiencesIds = array();
        foreach($results as $experience){
            $experiencesIds[$experience['id']] = $experience['id'];
        }

        return $experiencesIds;
    }

}