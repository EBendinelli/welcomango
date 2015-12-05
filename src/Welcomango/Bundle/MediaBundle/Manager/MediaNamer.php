<?php

namespace Welcomango\Bundle\MediaBundle\Manager;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class MediaNamer
 */
class MediaNamer implements NamerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorageInterface;

    /**
     * @param $tokenStorageInterface
     */
    function __construct(TokenStorageInterface $tokenStorageInterface)
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
     * @param $fileName
     *
     * @return string
     */
    public function getTempName($fileName)
    {
        return $this->getName($fileName);
    }

    protected function getName($fileName)
    {
        $currentUser = $this->tokenStorageInterface->getToken()->getUser();

        $explodeFilename   = explode('.', $fileName);
        $extension = '.'.end($explodeFilename);
        $filename = str_replace($extension, '', $fileName);

        return $filename.'_'.$currentUser->getId().$extension;
    }

}
