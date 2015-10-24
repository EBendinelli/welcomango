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

    /**
     * Create paginated and filtered query builder
     *
     * @param array $filters
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createPagerQueryBuilder(array $filters = array())
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($cities = $this->getFilter('city', $filters)) {
            foreach ($cities as $city) {
                $ORs[] = "u.city LIKE '%".$city."%'";
            }
            $queryBuilder->andWhere($queryBuilder->expr()->orX()->addMultiple($ORs));
        }

        if ($title = $this->getFilter('title', $filters)) {
            $queryBuilder->andWhere('e.title LIKE :title');
            $queryBuilder->setParameter('title', '%'.$title.'%');
        }

        if ($description = $this->getFilter('description', $filters)) {
            $queryBuilder->andWhere('e.description = :description');
            $queryBuilder->setParameter('description', $description);
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