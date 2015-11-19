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
            ->andWhere('a.deleted = false')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBestRated($limit){
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.bookings', 'b')
            ->where('a.published = true')
            ->andWhere('a.deleted = false')
            ->groupBy('a.id')
            ->orderBy('a.averageNote', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllExperiencesAttendedByUser($user){
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.bookings', 'b')
            ->where('a.published = true')
            ->where('a.deleted = false')
            ->andWhere('b.user ='.$user->getId())
            ->groupBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Create paginated and filtered query builder
     *
     * @param array $filters
     * @param boolean $isDeleted
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createPagerQueryBuilder(array $filters = array(), $isDeleted = false)
    {
        $queryBuilder = $this
            ->createQueryBuilder('e')
            ->leftJoin('e.participations', 'p')
            ->where('e.published = true');

        if(!$isDeleted) $queryBuilder->andWhere('e.deleted = false');

        if ($city = $this->getFilter('city', $filters)) {
            $queryBuilder->andWhere('e.city = :city');
            $queryBuilder->setParameter('city', $city);
        }

        if ($title = $this->getFilter('title', $filters)) {
            $queryBuilder->andWhere('e.title LIKE :title');
            $queryBuilder->orWhere('e.description LIKE :title');
            $queryBuilder->setParameter('title', '%'.$title.'%');
        }

        if ($date = $this->getFilter('date', $filters)) {

            $queryBuilder->andWhere('p.date LIKE :date');
            $queryBuilder->setParameter('date', $date->format('Y-m-d').'%');
        }

        if ($minParticipants = $this->getFilter('min_participants_accepted', $filters)) {
            $queryBuilder->andWhere('e.maximumParticipants >= '.$minParticipants);
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