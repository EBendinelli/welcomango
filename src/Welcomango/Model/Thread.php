<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 * @ORM\Table(name="wm_thread")
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="thread", cascade={"persist"})
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToOne(targetEntity="Booking", inversedBy="thread")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     */
    protected $booking;

    /**
     * @ORM\OneToMany(targetEntity="ThreadMetadata", mappedBy="thread", cascade={"all"})
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

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
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }
}
