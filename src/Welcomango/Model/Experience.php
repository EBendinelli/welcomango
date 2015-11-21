<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToMany(targetEntity="Media", inversedBy="experiences")
     * @ORM\JoinTable(name="wm_experiences_medias")
     **/
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;

    /**
     * Get id
     *
     * @return integer
     */

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured = false;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

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
        $this->availability->removeElement($availability);
    }

    /**
     * @param Availability $availability
     */
    public function addAvailability(Availability $availability)
    {
        $this->availability[] = $availability;
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
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $user
     */
    public function setCreator($creator)
    {
        $this->creator= $creator;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Experience
     */
    public function setPublished($published)
    {
        $this->$published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return Experience
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Experience
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return Experience
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->deleted;
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
        $this->tags           = new ArrayCollection();
        $this->booking        = new ArrayCollection();
        $this->medias         = new ArrayCollection();
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

    public function isAvailableForDate($bookingRequest)
    {
        // TODO: FUCKING CRITERIA NOT WORKING. NO QUERY EXECUTED, FIND WHY. Should replace the foreach
        /*$criteria = Criteria::create()
            ->where(Criteria::expr()->eq("startTime", $startTime))
            ->andWhere(Criteria::expr()->eq("isParticipant", 1))
            ->andWhere(Criteria::expr()->eq("status", 'Booked'));*/

        foreach($this->availabilities as $availability){
            if($bookingRequest->getStartDatetime()->format('YY-mm-dd') > $availability->getStartDate()
                && $bookingRequest->getEndDatetime()->format('YY-mm-dd') < $availability->getEndDate()
                && $bookingRequest->getExperience() ==  $availability->getExperience()
                && (strrpos(','.$bookingRequest->getStartDatetime()->format('w').',', $availability->getDay()) || $availability->getDay() == "*")
                && (strrpos(','.$bookingRequest->getStartDatetime()->format('G').',', $availability->getHour()) || $availability->getHour() == "*")
            ){
                return false;
            }
        }
        return true;

        /*$result = $this->participations->matching($criteria);
        return ($result->isEmpty()) ? true : false;*/
    }

    public function isAlreadyRequestedByUser($booking)
    {
        // TODO: FUCKING CRITERIA NOT WORKING. NO QUERY EXECUTED, FIND WHY. Should replace the foreach
        /*$criteria = Criteria::create()
            ->where(Criteria::expr()->eq("startTime", $participation->getStartTime()))
            ->andWhere(Criteria::expr()->eq("isParticipant", 1))
            ->andWhere(Criteria::expr()->eq("user", $participation->getUser()));*/

        foreach($this->bookings as $existingBooking){
            if($existingBooking->getStartDatetime() == $booking->getStartDatetime()
                && $existingBooking->getEndDatetime() == $booking->getEndDatetime()
                && $existingBooking->getUser() == $booking->getUser()
            ){
                return true;
            }
        }
        return false;

        /*$result = $this->participations->matching($criteria);
        return ($result->isEmpty()) ? true : false;*/
    }

    public function getNumberOfTimeAttended(){
        $count = 0;
        foreach($this->bookings as $booking){
            if($booking->getStatus() == 'Happened'){
                $count++;
            }
        }

        return $count;
    }

    public function getPendingRequest(){
        $count = 0;
        foreach($this->bookings as $booking){
            if($booking->getStatus() == 'Requested'){
                $count++;
            }
        }

        return $count;
    }

}
