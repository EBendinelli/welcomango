<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 * @ORM\Entity
 * @ORM\Table(name="wm_message")
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="messages")
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(targetEntity="MessageMetadata", mappedBy="message", cascade={"all"})
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * The origin message is the one which can be set at the experience booking
     *
     * @ORM\Column(name="origin_message", type="boolean", nullable=true)
     */
    protected $originMessage;

    /**
     * @return bool
     */
    public function getOriginMessage()
    {
        return $this->originMessage;
    }

    /**
     * @param bool $originMessage
     */
    public function setOriginMessage($originMessage)
    {
        $this->originMessage = $originMessage;
    }
}
