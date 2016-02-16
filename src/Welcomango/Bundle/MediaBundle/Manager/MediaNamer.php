<?php

namespace Welcomango\Bundle\MediaBundle\Manager;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Welcomango\Bundle\CoreBundle\Util\Util;

/**
 * Class MediaNamer
 */
class MediaNamer implements NamerInterface
{
    /**
     * @var TokenStorageInterface $tokenStorageInterface
     */
    protected $tokenStorageInterface;

    /**
     * @param TokenStorageInterface $tokenStorageInterface
     */
    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function name(FileInterface $file)
    {
        return $this->getName($file->getClientOriginalName());
    }

    /**
     * retrieve the real tempFileName
     *
     * @param string $fileName
     *
     * @return string
     */
    public function getTempName($fileName)
    {
        return $this->getName($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getSlugifyFileName($fileName)
    {
        return $this->getName($fileName, true);
    }

    protected function getName($fileName, $slugify = false)
    {
        $currentUser = $this->tokenStorageInterface->getToken()->getUser();

        $explodeFilename = explode('.', $fileName);
        $extension       = '.'.end($explodeFilename);
        $filename        = str_replace($extension, '', $fileName);

        if ($slugify) {
            return Util::slugify($filename.'_'.$currentUser->getId()).$extension;
        } else {
            return $filename.'_'.$currentUser->getId().$extension;
        }

    }
}
