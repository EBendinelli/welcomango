<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Experience
 *
 * @ORM\Table(name="wm_experience")
 * @ORM\Entity
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
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
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
     * @var ArrayCollection
     *
     * @ORM\Column(name="participations", type="integer")
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="user")
     */
    private $participations;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="users")
     * @ORM\JoinTable(name="wm_users_tags")
     **/
    private $tags;

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

    public function __construct() {
        $this->tags = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }
}
