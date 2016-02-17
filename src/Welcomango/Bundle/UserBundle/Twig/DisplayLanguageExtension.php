<?php

namespace Welcomango\Bundle\UserBundle\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Welcomango\Model\User;

/**
 * Class DisplayLanguageExtension
 */
class DisplayLanguageExtension extends \Twig_Extension
{

    /**
     * @var array
     */
    protected $levels;

    /**
     * __construct
     *
     * @param array                    $levels
     */
    public function __construct($levels)
    {
        $this->levels = $levels;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display_language', array($this, 'displayLanguage'))
        );
    }

    /**
     * @param ArrayCollection $languages
     *
     * @return string
     */
    public function displayLanguage($languages)
    {
        if($languages->isEmpty()){
            return '';
        }

        //TODO: return the icon once they are done
        $icons = array();
        foreach($languages as $languages){
            $icons[] = '<div class="m-b-5">'.$languages->getLanguage().' ('.$this->levels[$languages->getLevel()].')</div>';
        }
        $languageResult = implode('', $icons);
        return $languageResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_language_extension';
    }
}
