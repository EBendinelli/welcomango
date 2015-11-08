<?php

namespace Welcomango\Bundle\MessageBundle\Manager;

use FOS\MessageBundle\Event\MessageEvent;
use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sends messages
 *
 * We also add the send email logic in this Class
 */
class Sender implements SenderInterface
{
    /**
     * The message manager
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * The thread manager
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param MessageManagerInterface  $messageManager
     * @param ThreadManagerInterface   $threadManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(MessageManagerInterface $messageManager, ThreadManagerInterface $threadManager, EventDispatcherInterface $dispatcher)
    {
        $this->messageManager = $messageManager;
        $this->threadManager  = $threadManager;
        $this->dispatcher     = $dispatcher;
    }

    /**
     * Sends the message by persisting it to the message manager and undeletes
     * the thread for all participants.
     *
     * @param MessageInterface $message
     */
    public function send(MessageInterface $message)
    {
        $this->threadManager->saveThread($message->getThread(), false);
        $this->messageManager->saveMessage($message, false);
        //ldd($message);

        /* Note: Thread::setIsDeleted() depends on metadata existing for all
         * thread and message participants, so both objects must be saved first.
         * We can avoid flushing the object manager, since we must save once
         * again after undeleting the thread.
         */
        $message->getThread()->setIsDeleted(false);
        $this->messageManager->saveMessage($message);

        $this->dispatcher->dispatch(FOSMessageEvents::POST_SEND, new MessageEvent($message));
    }
}
