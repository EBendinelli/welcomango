<?php

namespace Welcomango\Bundle\MessageBundle\Manager;

use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\MessageBuilder\ReplyMessageBuilder;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;

/**
 * Factory for message builders
 */
class MessageComposer implements ComposerInterface
{
    /**
     * Message manager
     *
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * Thread manager
     *
     * @var ThreadManagerInterface
     */
    protected $threadManager;

    /**
     * @param MessageManagerInterface $messageManager
     * @param ThreadManagerInterface  $threadManager
     */
    public function __construct(MessageManagerInterface $messageManager, ThreadManagerInterface $threadManager)
    {
        $this->messageManager = $messageManager;
        $this->threadManager  = $threadManager;
    }

    /**
     * Starts composing a message, starting a new thread
     *
     * @return NewThreadMessageBuilder
     */
    public function newThread()
    {
        $thread  = $this->threadManager->createThread();
        $message = $this->messageManager->createMessage();

        return new NewThreadMessageBuilder($message, $thread);
    }

    /**
     * Starts composing a message in a reply to a thread
     *
     * @param ThreadInterface $thread
     *
     * @return ReplyMessageBuilder
     */
    public function reply(ThreadInterface $thread)
    {
        $message = $this->messageManager->createMessage();

        return new ReplyMessageBuilder($message, $thread);
    }
}
