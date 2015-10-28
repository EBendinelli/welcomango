<?php

namespace Welcomango\Bundle\Front\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;


class Builder extends ContainerAware
{
    const MENU_NAME = "Welcomango";
    /**
     * @var FactoryInterface $factory factory
     */
    private $factory;

    /**
     * @var TokenStorageInterface $tokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestStack $requestStack requestStack
     */
    private $requestStack;

    private $router;

    protected $authorizationChecker;

    /**
     * @param FactoryInterface              $factory              factory
     * @param SecurityContextInterface      $securityContext      security context
     * @param RequestStack                  $requestStack         requestStack
     * @param SecurityContextInterface      $securityContext      security context
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, RequestStack $requestStack, SecurityContext $securityContext, $router)
    {
        $this->factory              = $factory;
        $this->tokenStorage         = $tokenStorage;
        $this->requestStack         = $requestStack;
        $this->securityContext      = $securityContext;
        $this->router               = $router;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem(self::MENU_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu'));
        $menu->setExtra('toggler', true);

        /*$menu->addChild('menu.title.home', array(
            'route'          => 'front_homepage',
        ));*/

        $menu->addChild('menu.title.experiences', array(
            'route'          => 'front_experience_list',
        ));


        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user= $this->securityContext->getToken()->getUser();

            $menu->addChild('log Out', array(
                'route'          => 'fos_user_security_logout',
            ));

            $menu->addChild($user->getUsername(), array(
                'route'          => 'fos_user_profile_show',
            ));

            if($user->getMedias()->first()){
                $image = "<img src='/".$user->getMedias()->first()->getWebPath()."' class='front-menu-user-picture' />";
            }else{
                $image = "<img src='/img/front/profile.png' class='front-menu-user-picture' />";
            }
            $menu->addChild( $image ,
                array(
                    'route' => 'fos_user_profile_show',
                    'extras' => array(
                        'safe_label' => true
                    )
                )
            );
        }else{
            $loginLink = $this->router->generate('fos_user_security_login');
            $loginButton = "<a class='btn btn-sm btn-bordered btn-black block-title fs-12 hidden-sm hidden-xs' href='".$loginLink."' data-text='Login'>Login</a>";
            $menu->addChild( $loginButton ,
                array(
                    'route' => 'fos_user_security_login',
                    'extras' => array(
                        'safe_label' => true
                    )
                )
            );

            $registerLink = $this->router->generate('fos_user_registration_register');
            $registerButton = "<a class='btn btn-primary btn-cons' href='".$registerLink."' data-text='Login'>Sign up</a>";
            $menu->addChild( $registerButton ,
                array(
                    'route' => 'fos_user_security_login',
                    'extras' => array(
                        'safe_label' => true
                    )
                )
            );

        }


/*
        $menu->addChild('menu.title.people', array(
            'route'          => 'front_people_list',
        ));

        $menu->addChild('menu.title.about', array(
            'route'          => 'about',
        ));

        $menu->addChild('menu.title.contact_us', array(
            'route'          => 'contact_us',
        ));
*/
        return $menu;
    }
}
