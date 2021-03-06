<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Collections\ArrayCollection;
use Proxies\__CG__\Welcomango\Model\Availability;

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
            ->andWhere('a.publicationStatus = \'published\'')
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
            ->where('a.publicationStatus = \'published\'')
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
            ->where('a.publicationStatus = \'published\'')
            ->where('a.deleted = false')
            ->andWhere('b.user ='.$user->getId())
            ->groupBy('a.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCommentsForExperience($experience){
        $feedbacks = array();
        $bookings = $experience->getBookings();
        foreach($bookings as $booking){
            if($booking->getStatus() == 'Happened'){
                $bookingFeedbacks = $booking->getFeedbacks();
                foreach($bookingFeedbacks as $feedback){
                    if($feedback->IsValidated() && !$feedback->isDeleted()) {
                        $feedbacks[] = $feedback;
                    }
                }
            }
        }
        return $feedbacks;
    }

    /**
     * Create paginated and filtered query builder
     *
     * @param array $filters
     * @param boolean $isDeleted
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createPagerQueryBuilder(array $filters = array(), $admin = false)
    {
        $queryBuilder = $this
            ->createQueryBuilder('e')
            ->leftJoin('e.availabilities', 'a')
            ->leftJoin('e.tags', 't');

        if(!$admin){
            $queryBuilder
                ->andWhere('e.deleted = false')
                ->andWhere('e.publicationStatus = \'published\'');
        }


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

            //Check that the date is included in the available period
            $queryBuilder->andWhere('a.startDate <= :start_date');
            $queryBuilder->setParameter('start_date', $date->format('Y-m-d').'%');
            $queryBuilder->andWhere('a.endDate >= :end_date');
            $queryBuilder->setParameter('end_date', $date->format('Y-m-d').'%');

            //Check that the day is also available
            $queryBuilder->andWhere('a.day LIKE :day');
            $queryBuilder->setParameter('day', '%,'.$date->format('w').',%');
        }

        if(!$admin){
            //Check that the experience end_date is not passed
            $queryBuilder->andWhere('a.endDate >= :experience_end_date');
            $queryBuilder->setParameter('experience_end_date', date('Y-m-d').'%');
        }

        if ($hour = $this->getFilter('hour', $filters)) {
            $queryBuilder->andWhere('a.hour LIKE :hour');
            $queryBuilder->setParameter('hour', '%,'.$hour.',%');
        }

        if ($minParticipants = $this->getFilter('min_participants_accepted', $filters)) {
            $queryBuilder->andWhere('e.maximumParticipants >= '.$minParticipants);
        }

        if ($tags = $this->getFilter('tags', $filters)) {
            if($tags instanceof ArrayCollection && !$tags->isEmpty()) {
                $tagsId = $tags->toArray();
                $queryBuilder->andWhere('t.id IN (:tags)');
                $queryBuilder->setParameter('tags', $tagsId);
                $queryBuilder->addGroupBy("e.id");
                $queryBuilder->andHaving("COUNT(DISTINCT t.name) = :count");
                $queryBuilder->setParameter("count", sizeof($tags));
            }
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
