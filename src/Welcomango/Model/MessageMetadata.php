<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Welcomango\Model\Message;
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_metadata")
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="metadata")
     * @var \FOS\MessageBundle\Model\MessageInterface
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}
