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
        //Create an array with the forbidden dates
        $forbiddenDates = array();
        $availabilities = $experience->getAvailabilities();

        foreach ($availabilities as $availability) {
            $interval = \DateInterval::createFromDateString('1 day');
            $startDate = new \DateTime();
            $endDate = new \Datetime();
            $period = new \DatePeriod($startDate, $interval, $endDate->add(new \DateInterval('P1Y')));
            //First we set as forbidden the days outside of the availabilities boundaries
            foreach ($period as $day) {
                if ($day->format('Y-m-d') < $availability->getStartDate()->format('Y-m-d')
                    || $day->format('Y-m-d') > $availability->getEndDate()->format('Y-m-d')
                ) {
                    $forbiddenDates[] = $day->format('Y-m-d');
                }
            }

            //Then we take care of the days withing these boundaries
            $period = new \DatePeriod($availability->getStartDate(), $interval, $availability->getEndDate());
            foreach ($period as $day) {
                if (strrpos($availability->getDay(), ',' . $day->format('w') . ',') === false && $availability->getDay() != "*") {
                    $forbiddenDates[] = $day->format('Y-m-d');
                }
            }

            //Finally we remove the already booked experiences
            foreach ($period as $day) {
                foreach ($experience->getBookings() as $booking) {
                    if ($booking->getStartDatetime()->format('Y-m-d') == $day->format('Y-m-d')) {
                        $forbiddenDates[] = $day->format('Y-m-d');
                    }
                }
            }
        }

        return $forbiddenDates;
    }

    /**
     * Process upload media for experiences
     * This method will move the medias in temp into the experience directory
     *
     * @param Experience $experience
     * @param array      $mediasId
     */
    public function processUploadMedias(Experience $experience, $mediasId)
    {
        $medias = explode(',', $mediasId);
        foreach ($medias as $mediaId) {
            $media         = $this->mediaRepository->findOneById($mediaId);
            $mediaTempFile = Media::getUploadTmpRootDir().'/'.$media->getOriginalFilename();
            if ($this->filesystem->exists($mediaTempFile)) {
                $this->filesystem->copy($mediaTempFile, $media->getExperienceRootDir($experience->getId()).'/'.$media->getOriginalFilename());
                $this->filesystem->remove($mediaTempFile);
            }
            $experience->addMedia($media);
        }
    }
}
