<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ExperienceManager
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
    public function updateAverageNote($experience){
        $participationRepo = $this->entityManager->getRepository('Welcomango\Model\Participation');
        $newNote = $participationRepo->getAverageLocalNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }
}
