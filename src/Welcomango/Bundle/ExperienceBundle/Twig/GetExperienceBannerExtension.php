<?php

namespace Welcomango\Bundle\ExperienceBundle\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Welcomango\Model\Experience;

/**
 * Class DisplayExperienceBannerExtension
 */
class GetExperienceBannerExtension extends \Twig_Extension
{

    /**
     * @var SecurityContextInterface security context
     */
    private $securityContext;

    /**
     * @var Router $router
     */
    private $router;

    /**
     * @param Router $router
     * @param SecurityContextInterface $securityContext
     */
    public function __construct($router, SecurityContextInterface $securityContext)
    {
        $this->router = $router;
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_banner', [$this, 'getBanner'], ['is_safe' => ['html']])
        );
    }

    /**
     * @param  $experience
     *
     * @return string
     */
    public function getBanner( $experience)
    {

        if($experience->getMedias()->first()){
            //If the experience has a picture we use this one
            $medias = $experience->getMedias();
            foreach($medias as $media){
                if($media->isDefault()){
                    return $media->getPath().$media->getOriginalFilename();
                }
            }
            $firstMedia = $medias->first();
            return $firstMedia->getPath().$firstMedia->getOriginalFilename();

        }else{
            //Here we define the default image
            //The default picture is related to the first tag selected (or city)

            //WE USE THIS FOR NOW BUT IT SHOULD NOT BE THE FINAL SOLUTION
            //return 'bundles/welcomangocore/images/experience_default/places-'.rand(0,18).'.jpg';

            $tags = $experience->getTags();
            $firstTag =$tags->first();
            return 'bundles/welcomangocore/images/experience_default/'.str_replace('tag.', '', strtolower($firstTag->getName())).'.jpg';
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_experience_banner_extension';
    }
}
