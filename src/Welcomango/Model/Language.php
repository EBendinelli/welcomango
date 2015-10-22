<?php

namespace Welcomango\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * language
 *
 * @ORM\Table(name="wm_language")
 * @ORM\Entity
 */
class Language
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
     * @ORM\Column(name="language_code", type="string", length=2)
     */
    private $languageCode;

    /**
     * @var string
     *
     * @ORM\Column(name="flag_label", type="string", length=15)
     */
    private $flagLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255)
     */
    private $language;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SpokenLanguage", mappedBy="language", cascade={"persist", "remove"})
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
     * Set languageCode
     *
     * @param string $languageCode
     *
     * @return language
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * Get languageCode
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return language
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getFlagLabel()
    {
        return $this->flagLabel;
    }

    /**
     * @param string $flagLabel
     */
    public function setFlagLabel($flagLabel)
    {
        $this->flagLabel = $flagLabel;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpokenLanguages()
    {
        return $this->spokenLanguages;
    }

    /**
     * @param ArrayCollection $spokenLanguages
     */
    public function setSpokenLanguages($spokenLanguages)
    {
        $this->spokenLanguages = $spokenLanguages;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->spokenLanguages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add spokenLanguages
     *
     * @param \Welcomango\Model\SpokenLanguage $spokenLanguages
     * @return Language
     */
    public function addSpokenLanguage(\Welcomango\Model\SpokenLanguage $spokenLanguages)
    {
        $this->spokenLanguages[] = $spokenLanguages;

        return $this;
    }

    /**
     * Remove spokenLanguages
     *
     * @param \Welcomango\Model\SpokenLanguage $spokenLanguages
     */
    public function removeSpokenLanguage(\Welcomango\Model\SpokenLanguage $spokenLanguages)
    {
        $this->spokenLanguages->removeElement($spokenLanguages);
    }
}
