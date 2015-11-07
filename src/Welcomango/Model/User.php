<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Criteria;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\UserRepository")
 * @ORM\Table(name="wm_user")
 */
class User extends BaseUser implements ParticipantInterface
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
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
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
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="from_city_id", referencedColumnName="id")
     */
    private $fromCity;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="current_city_id", referencedColumnName="id")
     */
    private $currentCity;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"M", "F", "O"})
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=255, nullable=true)
     */
    private $occupation;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Media", inversedBy="users")
     * @ORM\JoinTable(name="wm_users_medias")
     **/
    private $medias;

    /**
     * @var float
     *
     * @ORM\Column(name="note_as_local", type="float", nullable=true)
     */
    private $noteAsLocal;

    /**
     * @var float
     *
     * @ORM\Column(name="note_as_traveler", type="float", nullable=true)
     */
    private $noteAsTraveler;

    /**
     * @return array
     */
    public static function getAvailableRoles()
    {
        return array(
            self::ROLE_SUPER_ADMIN => self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN       => self::ROLE_ADMIN,
            self::ROLE_USER        => self::ROLE_USER,
        );
    }

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
     * @param Language $language
     *
     * @return bool
     */
    public function hasSpokenLanguage($language)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("language", $language));

        $result = $this->spokenLanguages->matching($criteria);

        return ($result->isEmpty()) ? false : true;
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
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getFromCity()
    {
        return $this->fromCity;
    }

    /**
     * @param mixed $fromCity
     */
    public function setFromCity($fromCity)
    {
        $this->fromCity = $fromCity;
    }

    /**
     * @return mixed
     */
    public function getCurrentCity()
    {
        return $this->currentCity;
    }

    /**
     * @param mixed $currentCity
     */
    public function setCurrentCity($currentCity)
    {
        $this->currentCity = $currentCity;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        $birthdateTimestamp = $this->birthdate->getTimestamp();
        $birthDate          = date('d/m/Y', $birthdateTimestamp);

        $timeZone = new \DateTimeZone('Europe/Brussels');
        $age      = \DateTime::createFromFormat('d/m/Y', $birthDate, $timeZone)
            ->diff(new \DateTime('now', $timeZone))
            ->y;

        return $age;

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
        $spokenLanguage->setUser($this);
    }

    /**
     * @param SpokenLanguage $spokenLanguage
     */
    public function removeSpokenLanguage(SpokenLanguage $spokenLanguage)
    {
        $this->spokenLanguages->removeElement($spokenLanguage);
    }

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->spokenLanguages = new ArrayCollection();
        $this->participations  = new ArrayCollection();
        $this->medias          = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param ArrayCollection $medias
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    /**
     * Set noteAsLocal
     *
     * @param float $noteAsLocal
     */
    public function setNoteAsLocal($noteAsLocal)
    {
        $this->noteAsLocal = $noteAsLocal;
    }

    /**
     * Get noteAsLocal
     *
     * @return float
     */
    public function getNoteAsLocal()
    {
        return $this->noteAsLocal;
    }

    /**
     * Set noteAsTraveler
     *
     * @param float $noteAsTraveler
     */
    public function setNoteAsTraveler($noteAsTraveler)
    {
        $this->noteAsTraveler = $noteAsTraveler;
    }

    /**
     * Get noteAsTraveler
     *
     * @return float
     */
    public function getNoteAsTraveler()
    {
        return $this->noteAsTraveler;
    }

    /**
     * Add medias
     *
     * @param Media $medias
     *
     * @return User
     */
    public function addMedia(\Welcomango\Model\Media $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Welcomango\Model\Media $medias
     */
    public function removeMedia(\Welcomango\Model\Media $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get experience
     *
     * @return Experience
     */
    public function getExperience()
    {
        foreach ($this->participations as $participation) {
            if ($participation->getIsCreator()) {
                return $participation->getExperience();
            }
        }
    }

    /**
     * @return Array
     */
    public function getAttendedParticipations()
    {
        $attendedParticipations = array();
        foreach ($this->participations as $participation) {
            if ($participation->getIsParticipant() && $participation->getStatus() == 'Happened') {
                $attendedParticipations[] = $participation;
            }
        }

        return $attendedParticipations;
    }
}
