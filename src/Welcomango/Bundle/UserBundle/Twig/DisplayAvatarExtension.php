<?php

namespace Welcomango\Bundle\UserBundle\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Welcomango\Model\User;

/**
 * Class DisplayAvatarExtension
 */
class DisplayAvatarExtension extends \Twig_Extension
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
     * @param Router                   $router
     * @param SecurityContextInterface $securityContext
     */
    public function __construct($router, SecurityContextInterface $securityContext)
    {
        $this->router          = $router;
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display_avatar', [
                $this,
                'displayAvatar',
            ], ['is_safe' => ['html']]),
        );
    }

    /**
     * @param User   $user
     * @param bool   $withRoute
     * @param bool   $background
     * @param string $class
     *
     * @return string
     */
    public function displayAvatar(User $user, $withRoute = false, $background = false, $class = '')
    {
        $avatar = '';
        if ($user->getProfileMedia()) {
            $profileMedia = $user->getProfileMedia();
            if ($background) {
                //Use a background css trick instead of a simple img tag
                $avatar = '<div class="'.$class.' user-img-1" style="background-image:url('.$profileMedia->getPath().$profileMedia->getOriginalFilename().')"></div>';
            } else {
                $avatar = '<img src="'.$profileMedia->getPath().$profileMedia->getOriginalFilename().'" class="'.$class.'" />';
            }
        } else {
            //Check if there is a gravatar picture or use default
            /*$mailHash = md5( strtolower( trim( $user->getEmail())));
            $gravsrc = "http://www.gravatar.com/avatar/".$mailHash.'.?s=250';
            $gravcheck = "http://www.gravatar.com/avatar/".$mailHash."?d=404";
            $response = get_headers($gravcheck);
            if ($response[0] != "HTTP/1.0 404 Not Found"){
                $img = $gravsrc;
            }else{*/
            $img = '/img/front/faces/profile.png';
            //}

            //If not we use the default image
            if ($background) {
                //Use a background css trick instead of a simple img tag
                $avatar = '<div class="'.$class.' user-img-1" style="background-image:url('.$img.')" ></div>';
            } else {
                $avatar = '<img src="'.$img.'" class="'.$class.'"/>';
            }
        }

        if ($withRoute) {
            if ($this->securityContext->getToken()->getUser() == $user) {
                $routeToUser = $this->router->generate('fos_user_profile_show');
            } else {
                $routeToUser = $this->router->generate('front_user_view', array('user_id' => $user->getId()));
            }
            $avatar = '<a href="'.$routeToUser.'">'.$avatar.'</a>';
        }

        return $avatar;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_avatar_extension';
    }
}
