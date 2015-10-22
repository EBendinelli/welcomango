<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Filesystem\Filesystem;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="wm_media")
 */
class Media
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="mime_type", type="string", length=255)
     */
    protected $mime_type;

    /**
     * @ORM\Column(name="extension", type="string", length=255)
     */
    protected $extension;

    /**
     * @ORM\Column(name="size", type="integer")
     */
    protected $size;

    /**
     * @ORM\Column(name="filename", type="string", nullable=false)
     */
    protected $filename;

    /**
     * @ORM\Column(name="original_filename", type="string", nullable=true)
     */
    protected $originalFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @ORM\ManyToMany(targetEntity="Experience", mappedBy="medias")
     **/
    protected $experiences;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->mime_type = "unknown";
        $this->extension = "unknown";
        $this->filename  = "unknown";
        $this->size      = 0;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @param string $mime_type
     */
    public function setMimeType($mime_type)
    {
        $this->mime_type = $mime_type;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * @param string $originalFilename
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/medias';
    }

    /**
     * Do a manager !
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileSystem  = new Filesystem();
        $currentFile = $this->getFile();

        if ($this->getId()) {
            if ($fileSystem->exists($this->getUploadRootDir().'/'.$this->getFilename())) {
                $fileSystem->remove($this->getUploadRootDir().'/'.$this->getFilename());
            }
        }

        $this->setSize($currentFile->getSize());
        $this->setMimeType($currentFile->getMimeType());
        $this->setFilename($currentFile->getClientOriginalName());
        $this->setOriginalFilename($currentFile->getClientOriginalName());

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        $this->path = $this->getFile()->getClientOriginalName();

        $this->file = null;
    }

    /**
     * Add experiences
     *
     * @param \Welcomango\Model\Experience $experiences
     * @return Media
     */
    public function addExperience(\Welcomango\Model\Experience $experiences)
    {
        $this->experiences[] = $experiences;

        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \Welcomango\Model\Experience $experiences
     */
    public function removeExperience(\Welcomango\Model\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiences()
    {
        return $this->experiences;
    }
}
