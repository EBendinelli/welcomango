<?php

namespace Welcomango\Bundle\TagBundle\Twig;

use Welcomango\Model\SpokenLanguage;

/**
 * Class DisplayTagExtension
 */
class DisplayTagExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display_tag', array($this, 'displayTag'), ['is_safe' => ['html']])
        );
    }

    /**
     * @param ArrayCollection $tags
     *
     * @return string
     */
    public function displayTag($tags, $onlyText = false)
    {
        if($tags->isEmpty()){
            return '';
        }

        if($onlyText){
            foreach($tags as $tag){
                $icons[] = $tag->getName();
            }
            $tagsResult = implode(' | ', $icons);
            return $tagsResult;
        }

        $tagsResult = '<ul class="tags-container">';
        $icons = array();
        foreach($tags as $tag){
            $icons[] = '<li><img class="tags-svg m-b-5" src="/bundles/welcomangocore/images/icons/v2/'.str_replace(' ','', strtolower($tag->getName())).'.svg"><br/>'.$tag->getName().'</li>';
        }
        $tagsResult .= implode('', $icons).'</ul>';
        return $tagsResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_tag_extension';
    }
}
