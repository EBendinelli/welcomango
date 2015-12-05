<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;



/**
 * Comment
 *
 * @ORM\Table(name="wm_comment")
 * @ORM\Entity
 */
class Comment
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="postedComments")
     * @ORM\JoinColumn(name="poster_id", referencedColumnName="id", nullable=false)
     */
    private $poster;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="receivedComments")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id", nullable=false)
     */
    private $receiver;

    /**
     * @var Booking
     *
     * @ORM\ManyToOne(targetEntity="Booking", inversedBy="comments")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id", nullable=false)
     */
    private $booking;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    private $body;

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
     * @ORM\Column(name="validated", type="boolean")
     */
    private $validated = true;

    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured = false;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param User $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param User $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param Booking $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Set body
     *
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
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
     * Set validated
     *
     * @param boolean $validated
     *
     * @return boolean
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean
     */
    public function isValidated()
    {
        return $this->validated;
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
     * Get Featured
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->deleted;
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

}