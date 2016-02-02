<?php

namespace Welcomango\Bundle\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class UserManager
 */
class UserManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Update the average note based on existing booking
     *
     * @param User $user
     */
    public function updateAverageTravelerNote($user)
    {
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote     = $bookingRepo->getAverageNoteForUser($user);

        $user->setNoteAsLocal($newNote['average_note']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function updateAverageLocalNote($user)
    {
        $bookingRepo         = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $proposedExperiences = $user->getExperiences();
        $averageNotes        = array();
        foreach ($proposedExperiences as $experience) {
            $result         = $bookingRepo->getAverageNoteForExperience($experience);
            $averageNotes[] = $result['average_note'];
        }
        if (!empty($averageNotes)) {
            $newAverage = array_sum($averageNotes) / count($averageNotes);
            $user->setNoteAsTraveler($newAverage);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * @param ArrayCollection $spokenLanguages
     *
     * @return ArrayCollection
     */
    public function uniqueSpokenLanguage($spokenLanguages)
    {
        $usedLanguages     = array();
        $realUsedLanguages = new ArrayCollection();
        foreach ($spokenLanguages as $spokenLanguage) {
            if (!in_array($spokenLanguage->getLanguage(), $usedLanguages)) {
                $realUsedLanguages->add($spokenLanguage);
                $usedLanguages[] = $spokenLanguage->getLanguage();
            }
        }

        return $realUsedLanguages;
    }

}
