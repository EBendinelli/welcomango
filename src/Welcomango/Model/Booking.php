<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Booking
 *
 * @ORM\Table(name="wm_booking")
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\BookingRepository")
 */
class Booking
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_datetime", type="datetime")
     */
    private $startDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_datetime", type="datetime")
     */
    private $endDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="bookings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience", inversedBy="bookings")
     * @ORM\JoinColumn(name="experience_id", referencedColumnName="id")
     */
    private $experience;

    /**
     * @ORM\OneToMany(targetEntity="Feedback", mappedBy="booking", cascade={"persist", "remove"})
     **/
    private $feedbacks;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_participants", type="integer", nullable=true)
     */
    private $numberOfParticipants;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="Thread", mappedBy="booking", cascade={"persist"})
     **/
    private $thread;

    /**
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen = true;

    /**
     * @ORM\Column(name="action_required", type="boolean")
     */
    private $actionRequired = true;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDatetime
     *
     * @param \DateTime $startDatetime
     */
    public function setStartDatetime($startDatetime)
    {
        $this->startDatetime = $startDatetime;
    }

    /**
     * Get startDatetime
     *
     * @return \DateTime
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    /**
     * Set endDatetime
     *
     * @param \DateTime $endDatetime
     */
    public function setEndDatetime($endDatetime)
    {
        $this->endDatetime = $endDatetime;
    }

    /**
     * Get endDatetime
     *
     * @return \DateTime
     */
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Experience
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param Experience $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return ArrayCollection
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * @param ArrayCollection $feedbacks
     */
    public function setFeedbacks($feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }

    /**
     * @param Feedback $feedback
     */
    public function removeFeedback(Feedback $feedback)
    {
        $this->feedbacks->removeElement($feedback);
    }

    /**
     * @param Feedback $feedback
     */
    public function addFeedback(Feedback $feedback)
    {
        $this->feedbacks[] = $feedback;
    }

    /**
     * @return Boolean
     */
    public function hasFeedbackFromTraveler(){
        foreach($this->feedbacks as $feedback){
            if($feedback->getSender() == $this->user){
                return true;
            }
        }

        return false;
    }

    /**
     * @return Feedback
     */
    public function getFeedbackFromTraveler(){
        foreach($this->feedbacks as $feedback){
            if($feedback->getSender() == $this->user && !$feedback->isDeleted()){
                return $feedback;
            }
        }

        return false;
    }

    /**
     * @return Boolean
     */
    public function hasFeedbackFromLocal(){
        foreach($this->feedbacks as $feedback){
            if($feedback->getSender() == $this->experience->getCreator()){
                return true;
            }
        }

        return false;
    }

    /**
     * @return Feedback
     */
    public function getFeedbackFromLocal(){
        foreach($this->feedbacks as $feedback){
            if($feedback->getSender() == $this->experience->getCreator() && !$feedback->isDeleted()){
                return $feedback;
            }
        }

        return false;
    }

    /**
     * Set numberOfParticipants
     *
     * @param integer $numberOfParticipants
     */
    public function setNumberOfParticipants($numberOfParticipants)
    {
        $this->numberOfParticipants = $numberOfParticipants;
    }

    /**
     * Get numberOfParticipants
     *
     * @return integer
     */
    public function getNumberOfParticipants()
    {
        return $this->numberOfParticipants;
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
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     *
     * @return Boolean
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set actionRequired
     *
     * @param boolean $actionRequired
     *
     * @return Boolean
     */
    public function setActionRequired($actionRequired)
    {
        $this->actionRequired = $actionRequired;

        return $this;
    }

    /**
     * Get actionRequired
     *
     * @return boolean
     */
    public function getActionRequired()
    {
        return $this->actionRequired;
    }
}
