<?php

namespace Welcomango\Bundle\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Builder extends ContainerAware
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
        $menu = $this->factory->createItem(self::ADMIN_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu-items'));
        $menu->setExtra('toggler', true);

        $menu->addChild('menu.title.home', array(
            'route'          => 'homepage',
            'linkAttributes' => ['class' => 'fa fa-home']
        ));

        return $menu;
    }
}
