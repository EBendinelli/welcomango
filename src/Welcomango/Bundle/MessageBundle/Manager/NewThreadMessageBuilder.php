<?php

namespace Welcomango\Bundle\MessageBundle\Manager;

use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;

use Welcomango\Model\Booking;

/**
 * Class NewThreadMessageBuilder
 */
class NewThreadMessageBuilder extends AbstractMessageBuilder
{
    /**
     * The thread subject
     *
     * @param string $subject
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function setSubject($subject)
    {
        $this->thread->setSubject($subject);

        return $this;
    }

    /**
     * @param Booking $booking
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function setBooking($booking)
    {
        $this->thread->setBooking($booking);

        return $this;
    }

    /**
     * @param  ParticipantInterface $recipient
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function addRecipient(ParticipantInterface $recipient)
    {
        $this->thread->addParticipant($recipient);

        return $this;
    }

    /**
     * @param  Collection $recipients
     *
     * @return NewThreadMessageBuilder
     */
    public function addRecipients(Collection $recipients)
    {
        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }

        return $this;
    }
}
