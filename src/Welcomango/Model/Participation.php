<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="wm_participation")
 * @ORM\Entity
 */
class Participation
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
     * @var boolean
     *
     * @ORM\Column(name="is_creator", type="boolean")
     */
    private $isCreator;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_participant", type="boolean")
     */
    private $isParticipant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime")
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="participations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience", inversedBy="participations")
     * @ORM\JoinColumn(name="experience_id", referencedColumnName="id")
     */
    private $experience;

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
     * Set isCreator
     *
     * @param boolean $isCreator
     */
    public function setIsCreator($isCreator)
    {
        $this->isCreator = $isCreator;
    }

    /**
     * Get isCreator
     *
     * @return boolean 
     */
    public function getIsCreator()
    {
        return $this->isCreator;
    }

    /**
     * Set isParticipant
     *
     * @param boolean $isParticipant
     */
    public function setIsParticipant($isParticipant)
    {
        $this->isParticipant = $isParticipant;
    }

    /**
     * Get isParticipant
     *
     * @return boolean 
     */
    public function getIsParticipant()
    {
        return $this->isParticipant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
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
     * Set note
     *
     * @param integer $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        return $this->note;
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
}
