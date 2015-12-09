<?php

namespace Welcomango\Bundle\UserBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class RegistrationConfirmedListener
 */
class RegistrationConfirmedListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
        );
    }

    /**
     * @return mixed
     */
    public function onRegistrationCompleted()
    {
        return $this->router->generate('user_registration_check_email');
    }

    /**
     * @return mixed
     */
    public function onRegistrationConfirmed()
    {
        return $this->router->generate('fos_user_profile_show');
    }
}
