<?php

namespace Welcomango\Bundle\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @param FactoryInterface              $factory              factory
     * @param SecurityContextInterface      $securityContext      security context
     * @param RequestStack                  $requestStack         requestStack
     * @param SecurityContextInterface      $securityContext      security context
     * @param TranslatorInterface   $translator translator
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, RequestStack $requestStack, SecurityContext $securityContext, $router, TranslatorInterface $translator)
    {
        $this->factory              = $factory;
        $this->tokenStorage         = $tokenStorage;
        $this->requestStack         = $requestStack;
        $this->securityContext      = $securityContext;
        $this->router               = $router;
        $this->translator   = $translator;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem(self::MENU_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu'));
        $menu->setExtra('toggler', true);

        /*$menu->addChild('menu.title.home', array(
            'route'          => 'front_homepage',
        ));*/

        $menu->addChild($this->translator->trans('menu.title.experiences', array(), 'interface'), array(
            'route'          => 'front_experience_list',
        ));

        $menu->addChild($this->translator->trans('menu.title.howTo', array(), 'interface'), array(
            'route'          => 'page_view_slug',
            'routeParameters' => [
                'slug'  => 'how-to'
            ]
        ));

        $menu->addChild($this->translator->trans('menu.title.portraits', array(), 'interface'), array(
            'route'          => 'page_portrait_list',
        ));


        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user= $this->securityContext->getToken()->getUser();

            $menu->addChild('log Out', array(
                'route'          => 'fos_user_security_logout',
            ));

            $menu->addChild($user->getUsername(), array(
                'route'          => 'fos_user_profile_show',
            ));


            if ($profileMedia = $user->getProfileMedia()) {
                $image = "<img src='".$profileMedia->getPath().$profileMedia->getOriginalFilename()."' class='front-menu-user-picture' />";
            } else {
                $image = "<img src='/bundles/welcomangocore/images/profile.png' class='front-menu-user-picture' />";
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
            $loginButton = "<a data-toggle='modal' data-target='.modal-login' class='btn btn-sm btn-bordered btn-black block-title fs-12 hidden-sm hidden-xs' data-text='Login'>Login</a>";
            $menu->addChild( $loginButton ,
                array(

                    'extras' => array(
                        'safe_label' => true
                    )
                )
            );

            $registerLink = $this->router->generate('fos_user_registration_register');
            $registerButton = "<a class='btn btn-primary btn-cons' href='".$registerLink."' data-text='Login'>".$this->translator->trans('menu.title.signUp', array(), 'interface')."</a>";
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
