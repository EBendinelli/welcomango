<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;

use Welcomango\Bundle\ExperienceBundle\Validator\Constraints as WelcomangoAssert;

/**
 * Page
 *
 * @ORM\Table(name="wm_page")
 * @ORM\Entity
 */
class Page
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="pages")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", inversedBy="portrait")
     * @ORM\JoinColumn(name="portrayed_user_id", referencedColumnName="id")
     */
    private $portrayedUser;

    /**
     * @var Media
     * @ORM\ManyToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id", nullable=true)
     **/
    private $banner;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pages")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="publication_status", type="string")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"pending", "published", "deleted"})
     */
    private $publicationStatus = 'pending';

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getPortrayedUser()
    {
        return $this->portrayedUser;
    }

    /**
     * @param User $portrayedUser
     */
    public function setPortrayedUser($portrayedUser)
    {
        $this->portrayedUser = $portrayedUser;
    }

    /**
     * @return Media
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param Media $banner
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


    /**
     * Set publicationStatus
     *
     * @param string $publicationStatus
     *
     * @return Experience
     */
    public function setPublicationStatus($publicationStatus)
    {
        $this->publicationStatus = $publicationStatus;

        return $this;
    }

    /**
     * Get publicationStatus
     *
     * @return string
     */
    public function getPublicationStatus()
    {
        return $this->publicationStatus;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return boolean
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    public function __construct() {
        $this->categories = new ArrayCollection();
    }

}
