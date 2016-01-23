<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;

use Welcomango\Bundle\ExperienceBundle\Validator\Constraints as WelcomangoAssert;

/**
 * Experience
 *
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\ExperienceRepository")
 * @ORM\Table(name="wm_experience")
 * @ORM\HasLifecycleCallbacks()
 */
class Experience
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="experiences")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=false)
     */
    private $creator;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimated_duration", type="integer")
     */
    private $estimatedDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="minimum_duration", type="integer")
     */
    private $minimumDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum_duration", type="integer")
     */
    private $maximumDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_per_hour", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 800,
     *      minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $pricePerHour;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum_participants", type="integer")
     */
    private $maximumParticipants;

    /**
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="experience", cascade={"persist", "remove"})
     **/
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="Availability", mappedBy="experience", cascade={"persist", "remove"})
     **/
    private $availabilities;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="users")
     * @ORM\JoinTable(name="wm_experiences_tags")
     **/
    private $tags;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Media", inversedBy="experiences", cascade={"persist"})
     * @ORM\JoinTable(name="wm_experiences_medias")
     **/
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;

    /**
     * @ORM\Column(name="publication_status", type="string")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"pending", "published", "refused", "deleted"})
     */
    private $publicationStatus = 'pending';

    /**
     * @var string
     *
     * @ORM\Column(name="refused_for", type="string", length=255, nullable=true)
     */
    private $refusedFor;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="refusedExperiences")
     * @ORM\JoinColumn(name="moderated_by_id", referencedColumnName="id", nullable=true)
     */
    private $moderatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="refused_at", type="datetime", nullable=true)
     */
    private $refusedAt;

    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured = false;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(name="updated_status", type="boolean")
     */
    private $updatedStatus = false;

    /**
     * @ORM\Column(name="average_note", type="float", nullable=true)
     */
    private $averageNote;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Set estimatedDuration
     *
     * @param integer $estimatedDuration
     */
    public function setEstimatedDuration($estimatedDuration)
    {
        $this->estimatedDuration = $estimatedDuration;
    }

    /**
     * Get estimatedDuration
     *
     * @return integer
     */
    public function getEstimatedDuration()
    {
        return $this->estimatedDuration;
    }

    /**
     * Set minimumDuration
     *
     * @param integer $minimumDuration
     */
    public function setMinimumDuration($minimumDuration)
    {
        $this->minimumDuration = $minimumDuration;
    }

    /**
     * Get minimumDuration
     *
     * @return integer
     */
    public function getMinimumDuration()
    {
        return $this->minimumDuration;
    }

    /**
     * Set maximumDuration
     *
     * @param integer $maximumDuration
     */
    public function setMaximumDuration($maximumDuration)
    {
        $this->maximumDuration = $maximumDuration;
    }

    /**
     * Get maximumDuration
     *
     * @return integer
     */
    public function getMaximumDuration()
    {
        return $this->maximumDuration;
    }

    /**
     * Set pricePerHour
     *
     * @param integer $pricePerHour
     */
    public function setPricePerHour($pricePerHour)
    {
        $this->pricePerHour = $pricePerHour;
    }

    /**
     * Get pricePerHour
     *
     * @return integer
     */
    public function getPricePerHour()
    {
        return $this->pricePerHour;
    }

    /**
     * @return int
     */
    public function getMaximumParticipants()
    {
        return $this->maximumParticipants;
    }

    /**
     * @param int $maximumParticipants
     */
    public function setMaximumParticipants($maximumParticipants)
    {
        $this->maximumParticipants = $maximumParticipants;
    }

    /**
     * @return ArrayCollection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param ArrayCollection $bookings
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * @return ArrayCollection
     */
    public function getAvailabilities()
    {
        return $this->availabilities;
    }

    /**
     * @param ArrayCollection $availabilities
     */
    public function setAvailabilities($availabilities)
    {
        $this->availabilities = $availabilities;
    }

    /**
     * @param Availability $availability
     */
    public function removeAvailability(Availability $availability)
    {
        $this->availabilities->removeElement($availability);
    }

    /**
     * @param Availability $availability
     */
    public function addAvailability(Availability $availability)
    {
        $this->availabilities[] = $availability;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param User $user
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * Set publicationStatus
     *
     * @param string $publicationStatus
     *
     * @return Experience
     */
    public function setPublicationStatus($publicationStatus)
    {
        $this->publicationStatus = $publicationStatus;

        return $this;
    }

    /**
     * Get publicationStatus
     *
     * @return string
     */
    public function getPublicationStatus()
    {
        return $this->publicationStatus;
    }

    /**
     * Set refusedFor
     *
     * @param string $refusedFor
     */
    public function setRefusedFor($refusedFor)
    {
        $this->refusedFor = $refusedFor;
    }

    /**
     * Get refusedFor
     *
     * @return string
     */
    public function getRefusedFor()
    {
        return $this->refusedFor;
    }

    /**
     * @return mixed
     */
    public function getModeratedBy()
    {
        return $this->moderatedBy;
    }

    /**
     * @param mixed $moderatedBy
     */
    public function setModeratedBy($moderatedBy)
    {
        $this->moderatedBy = $moderatedBy;
    }

    /**
     * Set refusedAt
     *
     * @param \DateTime $refusedAt
     */
    public function setRefusedAt($refusedAt)
    {
        $this->refusedAt = $refusedAt;
    }

    /**
     * Get refusedAt
     *
     * @return \DateTime
     */
    public function getRefusedAt()
    {
        return $this->refusedAt;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return boolean
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }


    /**
     * Get Featured
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return boolean
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set updatedStatus
     *
     * @param boolean $updatedStatus
     *
     * @return boolean
     */
    public function setUpdatedStatus($updatedStatus)
    {
        $this->updatedStatus = $updatedStatus;

        return $this;
    }

    /**
     * Get updatedStatus
     *
     * @return boolean
     */
    public function hasUpdatedStatus()
    {
        return $this->updatedStatus;
    }

    /**
     * Set averageNote
     *
     * @param boolean $averageNote
     *
     * @return Experience
     */
    public function setAverageNote($averageNote)
    {
        $this->averageNote = $averageNote;

        return $this;
    }

    /**
     * Get averageNote
     *
     * @return float
     */
    public function getAverageNote()
    {
        return $this->averageNote;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
        //If not working try this:
        //$this->booking->remove($booking);
    }

    /**
     * @param Booking $booking
     */
    public function addBooking(Booking $booking)
    {
        $this->bookings[] = $booking;
    }

    /**
     * @param Booking $booking
     */
    public function removeBooking(Booking $booking)
    {
        $this->bookings->removeElement($booking);
        //If not working try this:
        //$this->booking->remove($booking);
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function createDate()
    {
        $this->setCreatedAt(new \Datetime());
    }

    public function __construct()
    {
        $this->tags    = new ArrayCollection();
        $this->booking = new ArrayCollection();
        $this->medias  = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param ArrayCollection $medias
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    /**
     * Add medias
     *
     * @param \Welcomango\Model\Media $medias
     *
     * @return Experience
     */
    public function addMedia(\Welcomango\Model\Media $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Welcomango\Model\Media $medias
     */
    public function removeMedia(\Welcomango\Model\Media $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Arrff
     */
    public function isAvailableForDate()
    {

    }

    /**
     * @return array
     *
     * This function returns the available days based on the availabilities attached to this experience
     */
    public function getAvailableDays($format = 'text')
    {
        $interval       = \DateInterval::createFromDateString('1 day');
        $availableDates = array();

        //This variable is used to store available hours for specific days
        $availableHours = array();
        foreach ($this->availabilities as $availability) {
            //First we get the available days according to the period defined and the days selected
            $endDate = $availability->getEndDate();
            // The interval doesn't include the last day if we don't do this
            $endDate->modify('+1 day');;
            $period = new \DatePeriod($availability->getStartDate(), $interval, $endDate);
            foreach ($period as $day) {
                if (strpos($availability->getDay(), ','.($day->format('N')-1).',') > -1 || $availability->getDay() == "*") {
                    if($format == 'text'){
                        $availableDates[$day->format('Y-m-d')] = $day->format('Y-m-d');
                    }elseif($format == 'datetime'){
                        $availableDates[$day->format('Y-m-d')] = $day;
                    }

                    //We get a string with the available hours for this day
                    //This string will be used as a basis
                    $availableHours[$day->format('Y-m-d')] = $availability->getHour();
                }
            }
        }

        //Then we remove the days which are FULLY booked
        //So we look for accepted booking happening on the available days
        $bookedHours = array();
        foreach ($this->bookings as $booking) {
            if (isset($availableDates[$booking->getStartDatetime()->format('Y-m-d')]) && $booking->getStatus() == 'Accepted') {

                //Store booking information in variables for clarity
                $bookingStartTime = $booking->getStartDatetime()->format('G');
                $bookingEndTime   = $booking->getEndDatetime()->format('G');
                $bookingDay       = $booking->getStartDatetime()->format('Y-m-d');

                $bookedHoursTmp = ',';
                for ($i = $bookingStartTime; $i <= $bookingEndTime; $i++) {
                    $bookedHoursTmp .= $i.',';
                }
                if(isset($bookedHours[$bookingDay])){
                    $bookedHours[$bookingDay] .= $bookedHoursTmp;
                }else{
                    $bookedHours[$bookingDay] = $bookedHoursTmp;
                }

                //Now we removed this booked hours from the available hours
                //This work most of the time but if there are more hours booked than available it doesn't
                $availableHours[$bookingDay] = str_replace($bookedHours[$bookingDay], '', $availableHours[$bookingDay]);
                if(empty($availableDates[$bookingDay])){
                    unset($availableDates[$bookingDay]);
                }
            }
        }

        //This function will handle the last case
        //For example if someone booked a 4h hour experience for morning (which means the substr didn't work)
        //We handle this by comparing the remaining tables
        foreach ($bookedHours as $day => $hoursTable) {
            $bookedHoursTable = explode(',', $hoursTable);
            $availableHoursTable = explode(',',$availableHours[$day] );

            //We clean the table (remove empty values and duplicates)
            $availableHoursTable = array_unique($availableHoursTable );
            $bookedHoursTable = array_unique($bookedHoursTable );
            $availableHoursTable = array_filter($availableHoursTable);
            $bookedHoursTable = array_filter($bookedHoursTable);

            if(count($bookedHoursTable) > count($availableHoursTable)){
                unset($availableDates[$day]);
            }
        }

        return $availableDates;
    }

    /**
     * @param $bookingRequest
     *
     * @return bool
     */
    public function isAvailableForBooking($bookingRequest)
    {

        foreach ($this->availabilities as $availability) {
            if ($bookingRequest->getStartDatetime()->format('Y-m-d') > $availability->getStartDate()->format('Y-m-d')
                && $bookingRequest->getEndDatetime()->format('Y-m-d') < $availability->getEndDate()->format('Y-m-d')
                && $bookingRequest->getExperience() == $availability->getExperience()
                && ((strpos($availability->getDay(), ','.($bookingRequest->getStartDatetime()->format('N')-1).',') > -1) || $availability->getDay() == "*")
                && ((strpos($availability->getHour(), ','.$bookingRequest->getStartDatetime()->format('G').',') > -1) || $availability->getHour() == "*")
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $booking
     *
     * @return bool
     */
    public function isAlreadyRequestedByUser($booking)
    {
        foreach ($this->bookings as $existingBooking) {
            if ($existingBooking->getStartDatetime() == $booking->getStartDatetime()
                && $existingBooking->getEndDatetime() == $booking->getEndDatetime()
                && $existingBooking->getUser() == $booking->getUser()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function getNumberOfTimeAttended()
    {
        $count = 0;
        foreach ($this->bookings as $booking) {
            if ($booking->getStatus() == 'Happened') {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return int
     */
    public function getPendingRequest()
    {
        $count = 0;
        foreach ($this->bookings as $booking) {
            if ($booking->getStatus() == 'Requested') {
                $count++;
            }
        }

        return $count;
    }

}
