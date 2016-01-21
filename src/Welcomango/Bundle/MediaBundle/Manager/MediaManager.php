<?php

namespace Welcomango\Bundle\MediaBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Welcomango\Model\Media;
use Welcomango\Model\Experience;
use Welcomango\Model\User;
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
     * @param MediaNamer    $mediaNamer
     * @param FilesystemMap $filesystemMap
     */
    public function __construct(MediaNamer $mediaNamer, FilesystemMap $filesystemMap)
    {
        $this->mediaNamer    = $mediaNamer;
        $this->filesystemMap = $filesystemMap;
    }

    /**
     * @param string $mediaList
     * @param mixed  $entity
     *
     * @return ArrayCollection
     */
    public function generateMediasFromCsv($mediaList, $entity)
    {
        $mediaCollection = new ArrayCollection();
        $mediaPrefix     = $this->getMediaPrefix($entity);
        $realAdapter     = $this->filesystemMap->get('real');
        $tempadapter     = $this->filesystemMap->get('gallery');
        $pathToUpload    = '/'.\date("Y").'/'.\date("m").'/';

        $medias = explode(',', $mediaList);

        $currentMedias     = $entity->getMedias();
        $currentMediasName = array();
        foreach ($currentMedias as $media) {
            $currentMediasName[] = $media->getOriginalFilename();
        }

        foreach ($medias as $media) {
            if ($media !== "") {
                //if one of the media has a different name it mean a new one has been added so we require a new validation
                if (!in_array($media, $currentMediasName) && $entity instanceof Experience) {
                    $entity->setPublicationStatus('pending');
                }
                $originalFileName = $media;
                $tempFileName     = $this->mediaNamer->getTempName($media);
                $mediaEntity      = new Media();
                $mediaEntity->setOriginalFilename($mediaPrefix.$originalFileName);
                $mediaEntity->setPath('/uploads'.$pathToUpload);
                $mediaEntity->addExperience($entity);
                $mediaCollection->add($mediaEntity);
                if (!$realAdapter->has($pathToUpload.$mediaPrefix.$originalFileName)) {
                    $fileContent = $tempadapter->read($tempFileName);
                    $realAdapter->write($pathToUpload.$mediaPrefix.$originalFileName, $fileContent);
                }
            }
        }

        return $mediaCollection;
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
