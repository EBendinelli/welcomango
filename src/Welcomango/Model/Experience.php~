<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Experience
 *
 * @ORM\Entity(repositoryClass="Welcomango\Model\Repository\ExperienceRepository")
 * @ORM\Table(name="wm_experience")
 * @ORM\HasLifecycleCallbacks()
 */
class Experience
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @var integer
     *
     * @ORM\Column(name="estimated_duration", type="integer")
     */
    private $estimatedDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="minimum_duration", type="integer")
     */
    private $minimumDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum_duration", type="integer")
     */
    private $maximumDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_per_hour", type="integer")
     */
    private $pricePerHour;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximum_participants", type="integer")
     */
    private $maximumParticipants;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="experience", cascade={"persist", "remove"})
     **/
    private $participations;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="users")
     * @ORM\JoinTable(name="wm_experiences_tags")
     **/
    private $tags;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Media", inversedBy="experiences")
     * @ORM\JoinTable(name="wm_experiences_medias")
     **/
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;

    /**
     * Get id
     *
     * @return integer
     */

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured = false;

    /**
     * @ORM\Column(name="average_note", type="float", nullable=true)
     */
    private $averageNote;

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
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Set estimatedDuration
     *
     * @param integer $estimatedDuration
     */
    public function setEstimatedDuration($estimatedDuration)
    {
        $this->estimatedDuration = $estimatedDuration;
    }

    /**
     * Get estimatedDuration
     *
     * @return integer
     */
    public function getEstimatedDuration()
    {
        return $this->estimatedDuration;
    }

    /**
     * Set minimumDuration
     *
     * @param integer $minimumDuration
     */
    public function setMinimumDuration($minimumDuration)
    {
        $this->minimumDuration = $minimumDuration;
    }

    /**
     * Get minimumDuration
     *
     * @return integer
     */
    public function getMinimumDuration()
    {
        return $this->minimumDuration;
    }

    /**
     * Set maximumDuration
     *
     * @param integer $maximumDuration
     */
    public function setMaximumDuration($maximumDuration)
    {
        $this->maximumDuration = $maximumDuration;
    }

    /**
     * Get maximumDuration
     *
     * @return integer
     */
    public function getMaximumDuration()
    {
        return $this->maximumDuration;
    }

    /**
     * Set pricePerHour
     *
     * @param integer $pricePerHour
     */
    public function setPricePerHour($pricePerHour)
    {
        $this->pricePerHour = $pricePerHour;
    }

    /**
     * Get pricePerHour
     *
     * @return integer
     */
    public function getPricePerHour()
    {
        return $this->pricePerHour;
    }

    /**
     * @return int
     */
    public function getMaximumParticipants()
    {
        return $this->maximumParticipants;
    }

    /**
     * @param int $maximumParticipants
     */
    public function setMaximumParticipants($maximumParticipants)
    {
        $this->maximumParticipants = $maximumParticipants;
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
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Experience
     */
    public function setPublished($published)
    {
        $this->$published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return Experience
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set averageNote
     *
     * @param boolean $averageNote
     *
     * @return Experience
     */
    public function setAverageNote($averageNote)
    {
        $this->averageNote = $averageNote;

        return $this;
    }

    /**
     * Get averageNote
     *
     * @return float
     */
    public function getAverageNote()
    {
        return $this->averageNote;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
        //If not working try this:
        //$this->participation->remove($participation);
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
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function createDate()
    {
        $this->setCreatedAt(new \Datetime());
    }

    public function getAuthor()
    {
        foreach ($this->participations as $participant) {
            if ($participant->getIsCreator()) {
                return $participant->getUser();
            }
        }
    }

    public function updateAverageNote(){
        /**
         * @todo develop experience manager to handle this
         */
    }

    public function __construct()
    {
        $this->tags           = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->medias         = new ArrayCollection();
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
}
