<?php

namespace Welcomango\Bundle\Front\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    /**
     * @param FactoryInterface              $factory              factory
     * @param SecurityContextInterface      $securityContext      security context
     * @param RequestStack                  $requestStack         requestStack
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->factory              = $factory;
        $this->tokenStorage         = $tokenStorage;
        $this->requestStack         = $requestStack;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem(self::MENU_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu'));
        $menu->setExtra('toggler', true);

            $menu->addChild('menu.title.home', array(
            'route'          => 'homepage',
        ));

        $menu->addChild('menu.title.experiences', array(
            'route'          => 'front_experience_list',
        ));

        $menu->addChild('menu.title.people', array(
            'route'          => 'experience_list',
        ));

        $menu->addChild('menu.title.about', array(
            'route'          => 'experience_list',
        ));

        $menu->addChild('menu.title.contactus', array(
            'route'          => 'experience_list',
        ));

        return $menu;
    }
}
