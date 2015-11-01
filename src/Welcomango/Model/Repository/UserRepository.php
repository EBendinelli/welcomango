<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 */

class UserRepository extends EntityRepository
{
    /**
     * Create paginated and filtered query builder
     *
     * @param array $filters
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createPagerQueryBuilder(array $filters = array())
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if ($roles = $this->getFilter('roles', $filters)) {
            foreach ($roles as $role) {
                $ORs[] = "u.roles LIKE '%".$role."%'";
            }
            $queryBuilder->andWhere($queryBuilder->expr()->orX()->addMultiple($ORs));
        }

        if ($username = $this->getFilter('username', $filters)) {
            $queryBuilder->andWhere('u.username LIKE :username');
            $queryBuilder->orWhere('u.email LIKE :username');
            $queryBuilder->orWhere('u.firstName LIKE :username');
            $queryBuilder->orWhere('u.lastName LIKE :username');
            $queryBuilder->setParameter('username', '%'.$username.'%');
        }

        if (is_bool($this->getFilter('enabled', $filters))) {
            $enabled = $this->getFilter('enabled', $filters);
            $queryBuilder->andWhere('u.enabled = :enabled');
            $queryBuilder->setParameter('enabled', $enabled);
        }

        if ($city = $this->getFilter('city', $filters)) {
            $queryBuilder->andWhere('u.currentCity = :city');
            $queryBuilder->setParameter('city', $city);
        }

        return $queryBuilder;
    }

    /**
     * Get filter from filters array
     *
     * @param string $key
     * @param array  $filters
     *
     * @return mixed
     */
    protected function getFilter($key, array $filters)
    {
        if (array_key_exists($key, $filters) && (null != $filters[$key] || is_bool($filters[$key]))) {
            return $filters[$key];
        }

        return null;
    }
}
