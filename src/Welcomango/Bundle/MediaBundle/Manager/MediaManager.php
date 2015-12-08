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
     * @param Experience $experience
     *
     * @return ArrayCollection
     */
    public function generateMediasFromCsv($mediaList, $experience)
    {
        $mediaCollection = new ArrayCollection();
        $adapter         = $this->filesystemMap->get('experience');
        $tempadapter     = $this->filesystemMap->get('gallery');

        $medias = explode(',', $mediaList);

        foreach ($medias as $media) {
            $originalFileName = $media;
            $tempFileName     = $this->mediaNamer->getTempName($media);
            $fileContent      = $tempadapter->read($tempFileName);
            $adapter->write('/'.$experience->getId().'/'.$originalFileName, $fileContent);
            $mediaEntity = new Media();
            $mediaEntity->setOriginalFilename($originalFileName);
            $mediaEntity->setPath('/medias/experiences/'.$experience->getId().'/');
            $mediaEntity->addExperience($experience);
            $mediaCollection->add($mediaEntity);
        }

        return $mediaCollection;

    }
}
