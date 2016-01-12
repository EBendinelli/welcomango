<?php

namespace Welcomango\Bundle\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @param FactoryInterface      $factory      factory
     * @param TokenStorageInterface $tokenStorage security context
     * @param RequestStack          $requestStack requestStack
     * @param TranslatorInterface   $translator translator
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->factory      = $factory;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->translator   = $translator;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem(self::ADMIN_NAME, array());

        $menu->setChildrenAttributes(array('class' => 'menu-items'));
        $menu->setExtra('toggler', true);

        $menu->addChild($this->translator->trans('menu.title.home', array(), 'admin'), array(
            'route'          => 'admin_homepage',
            'linkAttributes' => ['class' => 'fa fa-home'],
        ));

        $menu->addChild($this->translator->trans('menu.title.users', array(), 'admin'), array(
            'route'          => 'admin_user_list',
            'linkAttributes' => ['class' => 'fa fa-user'],
        ));

        $menu->addChild($this->translator->trans('menu.title.experiences', array(), 'admin'), array(
            'route'          => 'admin_experience_list',
            'linkAttributes' => ['class' => 'fa fa-hand-peace-o'],
        ));

        $menu->addChild($this->translator->trans('menu.title.languages', array(), 'admin'), array(
            'route'          => 'admin_language_list',
            'linkAttributes' => ['class' => 'fa fa-flag-o'],
        ));

        $menu->addChild($this->translator->trans('menu.title.medias', array(), 'admin'), array(
            'route'          => 'admin_media_list',
            'linkAttributes' => ['class' => 'fa fa-picture-o'],
        ));

        $menu->addChild($this->translator->trans('menu.title.booking', array(), 'admin'), array(
            'route'          => 'admin_booking_list',
            'linkAttributes' => ['class' => 'fa fa-cloud'],
        ));

        $menu->addChild($this->translator->trans('menu.title.feedback', array(), 'admin'), array(
            'route'          => 'admin_feedback_list',
            'linkAttributes' => ['class' => 'fa fa-comment'],
        ));

        $menu->addChild($this->translator->trans('menu.title.moderation.feedback', array(), 'admin'), array(
            'route'          => 'admin_moderation_feedback',
            'linkAttributes' => ['class' => 'pg-social'],
        ));

        $menu->addChild($this->translator->trans('menu.title.moderation.experience', array(), 'admin'), array(
            'route'          => 'admin_moderation_experience',
            'linkAttributes' => ['class' => 'fa-hand-lizard-o'],
        ));

        $menu->addChild($this->translator->trans('menu.title.tag', array(), 'admin'), array(
            'route'          => 'admin_tag_list',
            'linkAttributes' => ['class' => 'fa fa-tag'],
        ));

        $menu->addChild($this->translator->trans('menu.title.page', array(), 'admin'), array(
            'route'          => 'admin_page_list',
            'linkAttributes' => ['class' => 'fa fa-file-text-o'],
        ));

        return $menu;
    }
}
