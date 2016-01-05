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
    protected $mediaNamer;

    /**
     * @var FilesystemMap
     */
    protected $filesystemMap;

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
     * @param string     $mediaList
     * @param mixed      $entity
     *
     * @return ArrayCollection
     */
    public function generateMediasFromCsv($mediaList, $entity)
    {
        $mediaCollection = new ArrayCollection();
        $experienceAdapter   = $this->filesystemMap->get('experience');
        $userAdapter         = $this->filesystemMap->get('user');
        $tempadapter         = $this->filesystemMap->get('gallery');

        $medias = explode(',', $mediaList);

        foreach ($medias as $media) {
            if ($media !== "") {
                $originalFileName = $media;
                $tempFileName     = $this->mediaNamer->getTempName($media);
                $mediaEntity      = new Media();
                $mediaEntity->setOriginalFilename($originalFileName);
                if($entity instanceof Experience){
                    $mediaEntity->setPath('/medias/experiences/'.$entity->getId().'/');
                    $mediaEntity->addExperience($entity);
                }elseif($entity instanceof User){
                    $mediaEntity->setPath('/medias/users/'.$entity->getId().'/');
                    $mediaEntity->addUser($entity);
                }
                $mediaCollection->add($mediaEntity);
                if (!$experienceAdapter->has('/'.$entity->getId().'/'.$originalFileName)) {
                    $fileContent = $tempadapter->read($tempFileName);
                    $experienceAdapter->write('/'.$entity->getId().'/'.$originalFileName, $fileContent);
                }
                if (!$userAdapter->has('/'.$entity->getId().'/'.$originalFileName)) {
                    $fileContent = $tempadapter->read($tempFileName);
                    $userAdapter->write('/'.$entity->getId().'/'.$originalFileName, $fileContent);
                }


            }
        }
        return $mediaCollection;

    }
}
