<?php

namespace Welcomango\Bundle\BookingBundle\Manager;

use Doctrine\ORM\EntityManager;
use Welcomango\Model\Feedback;

class FeedbackManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createFeedback($booking, $user, $comment, $note){
        $feedback = new Feedback();
        $feedback->setBooking($booking);
        $feedback->setNote($note);
        $feedback->setComment($comment);
        $feedback->setSender($user);

        if($user == $booking->getUser()){
            $experience = $booking->getExperience();
            $feedback->setReceiver($experience->getCreator());
        }else{
            $feedback->setReceiver($booking->getUser());
        }
        $feedback->setCreatedAt(new \Datetime);
        $feedback->setUpdatedAt(new \Datetime);
        $feedback->setValidated(false);

        $this->entityManager->persist($feedback);
        $this->entityManager->flush();
    }

}

