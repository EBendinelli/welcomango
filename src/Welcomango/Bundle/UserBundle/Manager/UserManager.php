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

    //Update the average note based on existing participations
    public function updateAverageTravelerNote($user){
        $participationRepo = $this->entityManager->getRepository('Welcomango\Model\Participation');
        $newNote = $participationRepo->getAverageTravelerNote($user);

        $user->setNoteAsLocal($newNote['average_note']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateAverageLocalNote($user){
        $participationRepo = $this->entityManager->getRepository('Welcomango\Model\Participation');
        $proposedExperience = $user->getExperience();
        if($proposedExperience) {
            $newNote = $participationRepo->getAverageLocalNoteForExperience($proposedExperience);

            $user->setNoteAsTraveler($newNote['average_note']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }


}
