<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CityRepository
 */
class CityRepository extends EntityRepository
{
    /**
     * Returns results for autocomplete purpose
     *
     * @param string $query The query
     *
     * @return array
     */
    public function findForAutocomplete($query)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder
            ->select('c.id, c.name AS text')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%'.$query.'%')
        ;

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }
}
