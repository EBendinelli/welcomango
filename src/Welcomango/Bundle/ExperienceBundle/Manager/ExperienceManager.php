<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints\DateTime;
use Welcomango\Model\Media;
use Welcomango\Model\Experience;
use Welcomango\Model\User;
use Welcomango\Model\Availability;

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
     * @var availabilityManager
     */
    protected $availabilityManager;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param EntityManager $entityManager
     * @param EntityRepository $mediaRepository
     * @param Filesystem $filesystem
     */
    public function __construct(EntityManager $entityManager, EntityRepository $mediaRepository, Filesystem $filesystem, $availabilityManager)
    {
        $this->entityManager = $entityManager;
        $this->mediaRepository = $mediaRepository;
        $this->filesystem = $filesystem;
        $this->availabilityManager = $availabilityManager;
    }

    /**
     * Update the average note based on existing participations
     *
     * @param Experience $experience
     */
    public function updateAverageNote($experience)
    {
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote = $bookingRepo->getAverageNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    public function getForbiddenDatesForDatePicker($experience)
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

    public function getAvailablePeriodPerDate($experience){
        //get available dates
        $availableDays = $experience->getAvailableDays('datetime');
        $availablePeriodsPerDate = array();
        $availableHours = array();

        foreach($availableDays as $date){
            foreach($experience->getAvailabilities() as $availability){
                if (strrpos($availability->getDay(), ','.($date->format('N')-1).',') > -1 || $availability->getDay() == "*") {
                    $availablePeriodsPerDate[$date->format('d-m-Y')] = $this->availabilityManager->getAvailablePeriodForHours($availability->getHour());

                    //We get a string with the available hours for this day
                    //This string will be used as a basis
                    $availableHours[$date->format('d-m-Y')] = $availability->getHour();
                }
            }
        }

        //Now we remove the periods where something is already booked
        foreach ($experience->getBookings() as $booking) {
            if (isset($availablePeriodsPerDate[$booking->getStartDatetime()->format('d-m-Y')]) && $booking->getStatus() == 'Accepted') {
                //Store booking information in variables for clarity
                $bookingStartTime = $booking->getStartDatetime()->format('G');
                $bookingEndTime   = $booking->getEndDatetime()->format('G');
                $bookingDay       = $booking->getStartDatetime()->format('d-m-Y');

                $bookedHours = ',';
                for ($i = $bookingStartTime; $i <= $bookingEndTime; $i++) {
                    $bookedHours .= $i.',';
                }
                //Now we removed this booked hours from the available hours
                $availableHours[$bookingDay] = str_replace($bookedHours, '', $availableHours[$bookingDay]);

                //And we regenerate the periods available with the remaining hours
                $availablePeriodsPerDate[$bookingDay] = $this->availabilityManager->getAvailablePeriodForHours($availableHours[$bookingDay]);

                //Eventually, if their is no available hours remaining, we remove this day from the available ones
                if (empty($availablePeriodsPerDate[$bookingDay])) {
                    unset($availablePeriodsPerDate[$bookingDay]);
                }
            }
        }


        return $availablePeriodsPerDate;
    }

    /**
     * Change the status of the hasUpdatedStatus when this experience is viewed by its creator (so we don't keep notifying him)
     *
     * @param Experience $experience
     */
    public function clearUpdatedStatus($experience){
        $experience->setUpdatedStatus(false);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    /**
     * Prepare experience before creation to avoid having a huge controller
     *
     * @param Experience $experience
     * @param User $user
     *
     * @return Experience $experience
     */
    public function prepareExperienceForCreation($experience, $user){
        $experience->setCreator($user);
        $experience->setPublicationStatus('pending');

        //Set availabilities
        $availabilities = new ArrayCollection();
        $availability   = new Availability();
        $availability->setDay(array('7'));
        $availability->setHour(array('6'));

        //Set start and end date
        $today = new \Datetime;
        $aYearFromNow = new \Datetime;
        $aYearFromNow->add(new \DateInterval('P6M'));
        $availability->setStartDate($today);
        $availability->setEndDate($aYearFromNow);

        $availability->setExperience($experience);
        $availabilities->add($availability);
        $experience->setAvailabilities($availabilities);

    }

    /**
     * Check if the experience has availabities in the future
     *
     * @param Experience $experience
     */
    function checkIfStillAvailable($experience){
        //Max is set to old date
        $maxEndDate = new \DateTime('1990-03-01');

        //if there is a later date, it replaces max
        foreach($experience->getAvailabilities() as $availabilty){
            dump($availabilty->getEndDate());
            if($maxEndDate < $availabilty->getEndDate()){
                $maxEndDate = $availabilty->getEndDate();
            }
        }

        // if maxdate is inferior to today then the experience cannot be booked in the future so it's expired
        if($maxEndDate < new \DateTime()){
            $experience->setPublicationStatus('expired');
        }
    }
}
