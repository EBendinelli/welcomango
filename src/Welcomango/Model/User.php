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
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN       = 'ROLE_ADMIN';
    const ROLE_USER        = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
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
     * @ORM\OneToMany(targetEntity="SpokenLanguage", mappedBy="user", cascade={"persist", "remove"})
     */
    private $spokenLanguages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="user")
     */
    private $participations;

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
     *
     * @return string
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
     *
     * @return string
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
     *
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
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return string
     */
    public function displayName()
    {
        return $this->firstName.' '.substr($this->lastName, 0, 1).'.';
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param ArrayCollection $participations
     */
    public function setParticipations($participations)
    {
        $this->participations = $participations;
    }

    /**
     * @param Participation $participation
     */
    public function addParticipation(Participation $participation)
    {
        $this->participations[] = $participation;
    }

    /**
     * @param Participation $participation
     */
    public function removeParticipation(Participation $participation)
    {
        $this->participations->removeElement($participation);
        //If not working try this:
        //$this->participation->remove($participation);
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

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->spokenLanguages = new ArrayCollection();
        $this->participations  = new ArrayCollection();
    }
}
