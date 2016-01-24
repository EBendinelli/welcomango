<?php

namespace Welcomango\Bundle\MediaBundle\Manager;

use Doctrine\ORM\EntityManager;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Welcomango\Model\Media;
use Welcomango\Model\User;
use Welcomango\Model\Experience;
use Welcomango\Bundle\MediaBundle\Manager\MediaNamer;

/**
 * Class MediaManager
 */
class MediaManager
{
    /**
     * @var MediaNamer
     */
    private $mediaNamer;

    /**
     * @var FilesystemMap
     */
    private $filesystemMap;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $uploadsBaseDir;

    /**
     * @param MediaNamer    $mediaNamer
     * @param FilesystemMap $filesystemMap
     * @param EntityManager $entityManager
     * @param string        $uploadsBaseDir
     */
    public function __construct(MediaNamer $mediaNamer, FilesystemMap $filesystemMap, EntityManager $entityManager, $uploadsBaseDir)
    {
        $this->mediaNamer     = $mediaNamer;
        $this->filesystemMap  = $filesystemMap;
        $this->entityManager  = $entityManager;
        $this->uploadsBaseDir = $uploadsBaseDir;
    }

    /**
     * @param Experience $experience
     * @param array      $originalMedias
     */
    public function processMediasExperience($experience, $originalMedias = array())
    {
        $realAdapter  = $this->filesystemMap->get('real');
        $tempadapter  = $this->filesystemMap->get('gallery');
        $pathToUpload = '/'.\date("Y").'/'.\date("m").'/';
        $mediaPrefix  = $this->getMediaPrefix($experience);

        if (!empty($originalMedias)) {
            foreach ($originalMedias as $originalMedia) {
                if (false === $experience->getMedias()->contains($originalMedia)) {
                    $this->entityManager->remove($originalMedia);
                    $realAdapter->delete($pathToUpload.$originalMedia->getOriginalFilename());
                }
            }
        }

        foreach ($experience->getMedias() as $media) {
            $tempFileName = $this->mediaNamer->getTempName($media->getOriginalFilename());
            if ($tempadapter->has($tempFileName)) {
                $fileContent = $tempadapter->read($tempFileName);
                if (!$realAdapter->has($pathToUpload.$mediaPrefix.$media->getOriginalFilename())) {
                    $media->setPath($this->uploadsBaseDir.$pathToUpload);
                    $media->setExperience($experience);
                    $media->setOriginalFileName($mediaPrefix.$media->getOriginalFilename());
                    $realAdapter->write($pathToUpload.$media->getOriginalFilename(), $fileContent);
                    $tempadapter->delete($tempFileName);
                    $this->entityManager->persist($media);
                }
            }
        }
    }

    /**
     * @param string $mediaName
     * @param Media  $entity
     *
     * @return ArrayCollection
     */
    public function generateSimpleMedia($mediaName, $entity)
    {
        $realAdapter  = $this->filesystemMap->get('real');
        $tempadapter  = $this->filesystemMap->get('gallery');
        $pathToUpload = '/'.\date("Y").'/'.\date("m").'/';

        $currentMedia = $entity->getProfileMedia();
        if ($currentMedia instanceof Media) {
            if ($realAdapter->has($pathToUpload.$currentMedia->getOriginalFilename())) {
                $realAdapter->delete($pathToUpload.$currentMedia->getOriginalFilename());
            }
        } else {
            $currentMedia = new Media();
        }

        $mediaPrefix = $this->getMediaPrefix($entity);
        if ($mediaName !== "") {
            $tempFileName = $this->mediaNamer->getTempName($mediaName);
            $currentMedia->setOriginalFilename($mediaPrefix.$mediaName);
            $currentMedia->setPath('/uploads'.$pathToUpload);
            if (!$realAdapter->has('/uploads'.$pathToUpload.$mediaName)) {
                $fileContent = $tempadapter->read($tempFileName);
                $realAdapter->write($pathToUpload.$mediaPrefix.$mediaName, $fileContent);
            }
        }

        return $currentMedia;
    }

    /**
     * @param mixed $entity
     *
     * @return string
     */
    private function getMediaPrefix($entity)
    {
        if ($entity instanceof Experience) {
            return 'experience_'.$entity->getId().'_';
        } elseif ($entity instanceof User) {
            return 'user_'.$entity->getId().'_';
        } else {
            return '';
        }
    }
}
