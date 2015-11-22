<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

use Welcomango\Model\Media;
use Welcomango\Model\Experience;

/**
 * Class ExperienceManager
 */
class ExperienceManager
{
    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $mediaRepository;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param EntityManager    $entityManager
     * @param EntityRepository $mediaRepository
     * @param Filesystem       $filesystem
     */
    public function __construct(EntityManager $entityManager, EntityRepository $mediaRepository, Filesystem $filesystem)
    {
        $this->entityManager   = $entityManager;
        $this->mediaRepository = $mediaRepository;
        $this->filesystem      = $filesystem;
    }

    /**
     * Update the average note based on existing participations
     *
     * @param Experience $experience
     */
    public function updateAverageNote($experience)
    {
        $bookingRepo = $this->entityManager->getRepository('Welcomango\Model\Booking');
        $newNote     = $bookingRepo->getAverageLocalNoteForExperience($experience);

        $experience->setAverageNote($newNote['average_note']);
        $this->entityManager->persist($experience);
        $this->entityManager->flush();
    }

    /**
     * Process upload media for experiences
     * This method will move the medias in temp into the experience directory
     *
     * @param Experience $experience
     * @param array      $mediasId
     */
    public function processUploadMedias(Experience $experience, $mediasId)
    {
        $medias = explode(',', $mediasId);
        foreach ($medias as $mediaId) {
            $media         = $this->mediaRepository->findOneById($mediaId);
            $mediaTempFile = Media::getUploadTmpRootDir().'/'.$media->getOriginalFilename();
            if ($this->filesystem->exists($mediaTempFile)) {
                $this->filesystem->copy($mediaTempFile, $media->getExperienceRootDir($experience->getId()).'/'.$media->getOriginalFilename());
                $this->filesystem->remove($mediaTempFile);
            }
            $experience->addMedia($media);
        }
    }
}
