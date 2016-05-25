<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Availability
 *
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\AvailabilityRepository")
 * @ORM\Table(name="wm_availability")
 */
class Availability
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
     * @var integer
     *
     * @ORM\Column(name="day", type="string", nullable=false)
     */
    private $day;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="string")
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="hour", type="string", nullable=false)
     */
    private $hour;

    /**
     * @var dime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var date
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience", inversedBy="availabilities")
     * @ORM\JoinColumn(name="experience_id", referencedColumnName="id", nullable=false)
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
     * Set day
     *
     * @param integer $day
     * @return Availability
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return integer 
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set month
     *
     * @param integer $month
     * @return Availability
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set hour
     *
     * @param integer $hour
     * @return Availability
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return integer 
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Availability
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Availability
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
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
