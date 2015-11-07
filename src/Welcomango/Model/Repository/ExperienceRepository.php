<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ExperienceRepository
 *
 */

class ExperienceRepository extends EntityRepository
{

    public function getFeatured($limit){
        return $this
            ->createQueryBuilder('a')
            ->where('a.featured = true')
            ->andWhere('a.published = true')
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
            ->orderBy('a.averageNote', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
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

    /**
     * Create paginated and filtered query builder
     *
     * @param array $filters
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createPagerQueryBuilder(array $filters = array())
    {
        $queryBuilder = $this
            ->createQueryBuilder('e')
            ->leftJoin('e.participations', 'p')
            ->where('e.published = true');

        if ($city = $this->getFilter('city', $filters)) {
            $queryBuilder->andWhere('e.city = :city');
            $queryBuilder->setParameter('city', $city);
        }

        if ($title = $this->getFilter('title', $filters)) {
            $queryBuilder->andWhere('e.title LIKE :title');
            $queryBuilder->orWhere('e.description LIKE :title');
            $queryBuilder->setParameter('title', '%'.$title.'%');
        }

        if ($title = $this->getFilter('startDate', $filters)) {
            $queryBuilder->andWhere('e.title LIKE :title');
        }

        /*if ($title = $this->getFilter('endDate', $filters)) {
            $queryBuilder->andWhere('e.fecha BETWEEN :monday AND :sunday')
                ->setParameter('monday', $monday->format('Y-m-d'))
                ->setParameter('sunday', $sunday->format('Y-m-d'));
        }*/

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