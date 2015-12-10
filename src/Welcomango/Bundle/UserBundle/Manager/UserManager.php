<?php

namespace Welcomango\Bundle\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UserManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    //Update the average note based on existing booking
    public function updateAverageTravelerNote($user){
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote = $bookingRepo->getAverageNoteForUser($user);

        $user->setNoteAsLocal($newNote['average_note']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateAverageLocalNote($user){
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $proposedExperiences = $user->getExperiences();
        $averageNotes = array();
        foreach($proposedExperiences as $experience) {
            $result = $bookingRepo->getAverageNoteForExperience($experience);
            $averageNotes[] = $result['average_note'];
        }
        if(!empty($averageNotes)){
            $newAverage = array_sum($averageNotes) / count($averageNotes);
            $user->setNoteAsTraveler($newAverage);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }


}
