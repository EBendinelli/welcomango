<?php

namespace Welcomango\Bundle\MessageBundle\Manager;

use Welcomango\Model\Participation;
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
    protected $repository;

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
     * @param Participation $participation
     * @param User          $user
     * @param string        $body
     *
     * @return mixed
     */
    public function createThread($participation, $user, $body)
    {
        $message = $this->messageComposer->newThread()
            ->setSender($user)
            ->addRecipient($participation->getExperience()->getAuthor())
            ->setSubject($participation->getExperience()->getTitle().' - Messages')
            ->setBody($body)
            ->setParticipation($participation)
            ->getMessage();

        $this->sender->send($message);

        return $message->getThread();
    }
}
