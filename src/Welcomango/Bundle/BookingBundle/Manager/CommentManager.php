<?php

namespace Welcomango\Bundle\BookingBundle\Manager;

use Doctrine\ORM\EntityManager;
use Welcomango\Model\Comment;

class CommentManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createComment($booking, $user, $commentBody){
        $comment = new Comment();
        $comment->setBooking($booking);
        $comment->setPoster($user);
        if($user == $booking->getUser()){
            $experience = $booking->getExperience();
            $comment->setReceiver($experience->getCreator());
        }else{
            $comment->setReceiver($booking->getUser());
        }
        $comment->setBody($commentBody);
        $comment->setCreatedAt(new \Datetime);
        $comment->setUpdatedAt(new \Datetime);
        $comment->setValidated(false);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

}

