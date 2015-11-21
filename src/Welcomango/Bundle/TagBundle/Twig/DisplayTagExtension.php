<?php

namespace Welcomango\Bundle\TagBundle\Twig;

use Welcomango\Model\Tag;

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
            new \Twig_SimpleFunction('display_tag', array($this, 'displayTag'))
        );
    }

    /**
     * @param ArrayCollection $tags
     *
     * @return string
     */
    public function displayTag($tags)
    {
        if($tags->isEmpty()){
            return '';
        }

        //TODO: return the icon once they are done
        $icons = array();
        foreach($tags as $tag){
            $icons[] = $tag->getName();
        }
        $tagsResult = implode(' | ', $icons);
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
