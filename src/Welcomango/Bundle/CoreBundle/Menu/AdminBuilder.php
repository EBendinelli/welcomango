<?php

namespace Welcomango\Bundle\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Builder
 */
class AdminBuilder extends ContainerAware
{
    const ADMIN_NAME = "Welcomango";
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

    /**
     * @param FactoryInterface      $factory      factory
     * @param TokenStorageInterface $tokenStorage security context
     * @param RequestStack          $requestStack requestStack
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->factory      = $factory;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem(self::ADMIN_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu-items'));
        $menu->setExtra('toggler', true);

        $menu->addChild('menu.title.home', array(
            'route'          => 'admin_homepage',
            'linkAttributes' => ['class' => 'fa fa-home'],
        ));

        $menu->addChild('menu.title.users', array(
            'route'          => 'admin_user_list',
            'linkAttributes' => ['class' => 'fa fa-user'],
        ));

        $menu->addChild('menu.title.experiences', array(
            'route'          => 'admin_experience_list',
            'linkAttributes' => ['class' => 'fa fa-hand-peace-o'],
        ));

        $menu->addChild('menu.title.languages', array(
            'route'          => 'admin_language_list',
            'linkAttributes' => ['class' => 'fa fa-flag-o'],
        ));

        $menu->addChild('menu.title.medias', array(
            'route'          => 'admin_media_list',
            'linkAttributes' => ['class' => 'fa fa-picture-o'],
        ));

        $menu->addChild('menu.title.booking', array(
            'route'          => 'admin_booking_list',
            'linkAttributes' => ['class' => 'fa fa-cloud'],
        ));

        $menu->addChild('menu.title.tag', array(
            'route'          => 'admin_tag_list',
            'linkAttributes' => ['class' => 'fa fa-tag'],
        ));

        return $menu;
    }
}
