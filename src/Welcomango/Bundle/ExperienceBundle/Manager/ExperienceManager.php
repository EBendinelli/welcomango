<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;


use Welcomango\Model\Media;
use Welcomango\Model\Experience;

/**
 * Class ExperienceManager
 */
class ExperienceManager
{
    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $mediaRepository;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param EntityManager $entityManager
     * @param EntityRepository $mediaRepository
     * @param Filesystem $filesystem
     */
    public function __construct(EntityManager $entityManager, EntityRepository $mediaRepository, Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;
        $this->mediaRepository = $mediaRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * Update the average note based on existing participations
     *
     * @param Experience $experience
     */
    public function updateAverageNote($experience)
    {
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote = $bookingRepo->getAverageLocalNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    public function getAvailableDatesForDatePicker($experience)
    {
        // Create an array with the forbidden dates
        $forbiddenDates = array();
        $availableDays = $experience->getAvailableDays();

        // now we remove this available days from a list of days for the upcoming year
        $interval = \DateInterval::createFromDateString('1 day');
        $startDate = new \DateTime();
        $endDate = new \Datetime();
        $period = new \DatePeriod($startDate, $interval, $endDate->add(new \DateInterval('P1Y')));

        foreach ($period as $day) {
            if(!isset($availableDays[$day->format('Y-m-d')])) {
                $forbiddenDates[] = $day->format('Y-m-d');
            }
        }

        return $forbiddenDates;
    }
}
