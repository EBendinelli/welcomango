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
        $newNote = $participationRepo->getAverageNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    // TODO review this function, cleaner than doing the one above a thousand time...
    /*//Update the average note based on existing participations
    public function updateAllAverageNote($experiences){
        $participationRepo = $this->entityManager->getRepository('Welcomango\Model\Participation');

        $newNotes = $participationRepo
            ->createQueryBuilder('p')
            ->select("AVG(p.note) as average_note")
            ->addSelect("p.experience")
            ->where('p.isParticipant = 1')
            ->groupBy('p.experience')
            ->getQuery()
            ->getResult()
        ;

        dump($newNotes);
        die();
        /*$experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }*/

}
