<?php

namespace Welcomango\Bundle\MessageBundle\Manager;

use Welcomango\Model\Booking;
use Welcomango\Bundle\MessageBundle\Manager\Sender;
use Welcomango\Bundle\MessageBundle\Manager\MessageComposer;

/**
 * Class CreateMessageManager
 */
class CreateMessageManager
{
    /**
     * @var MessageComposer
     */
    protected $messageComposer;

    /**
     * @var Sender
     */
    protected $sender;

    /**
     * Constructor.
     *
     * @param MessageComposer $messageComposer
     * @param Sender          $sender
     */
    public function __construct(MessageComposer $messageComposer, Sender $sender)
    {
        $this->messageComposer = $messageComposer;
        $this->sender          = $sender;
    }

    /**
     * @param Booking $booking
     * @param User          $user
     * @param string        $body
     *
     * @return mixed
     */
    public function createThread($booking, $sender, $recipient, $body)
    {

        $message = $this->messageComposer->newThread()
            ->setSender($sender)
            ->addRecipient($recipient)
            ->setSubject('message.requestFor')
            ->setBody($body)
            ->setBooking($booking)
            ->getMessage();

        $this->sender->send($message);

        return $message->getThread();
    }
}
