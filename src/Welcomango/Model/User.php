<?php

namespace Welcomango\Model;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\UserRepository")
 * @ORM\Table(name="wm_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(name="spoken_languages", type="integer")
     * @ORM\OneToMany(targetEntity="SpokenLanguage", mappedBy="user")
     */
    private $spokenLanguages;


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
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     *
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Set spokenLanguages
     *
     * @param ArrayCollection $spokenLanguages
     *
     * @return SpokenLanguage
     */
    public function setSpokenLanguages(ArrayCollection $spokenLanguages)
    {
        $this->$spokenLanguages = $spokenLanguages;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpokenLanguages()
    {
        return $this->spokenLanguages;
    }

    /**
     * @param SpokenLanguage $spokenLanguage
     */
    public function addSpokenLanguage(SpokenLanguage $spokenLanguage)
    {
        $this->spokenLanguages[] = $spokenLanguage;
    }

    /**
     * @param SpokenLanguage $spokenLanguage
     */
    public function removeSpokenLanguage(SpokenLanguage $spokenLanguage)
    {
        $this->spokenLanguages->removeElement($spokenLanguage);
        //If not working try this:
        //$this->spokenLanguages->remove($spokenLanguage);
    }

    public function __construct()
    {
        parent::__construct();
        $this->spokenLanguages = new ArrayCollection();
    }
}