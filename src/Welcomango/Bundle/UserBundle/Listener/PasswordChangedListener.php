<?php

namespace Welcomango\Bundle\UserBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PasswordChangedListener
 */
class PasswordChangedListener implements EventSubscriberInterface
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
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onPasswordChanged',
        );
    }

    /**
     * @return mixed
     */
    public function onPasswordChanged(FormEvent $event)
    {

        $url = $this->router->generate('fos_user_profile_edit', ['activeTab' => 'about']);
        $event->setResponse(new RedirectResponse($url));

        /*return $this->render('WelcomangoCoreBundle:Core:success.html.twig', array(
            'title'           => $this->trans('contact.sent.title', array(), 'interface'),
            'sub_title'       => $this->trans('contact.sent.subTitle', array(), 'interface'),
            'message'         => $this->trans('contact.sent.message', array(), 'interface'),
            'button1_path'    => $this->get('router')->generate('front_homepage'),
            'button1_message' => $this->trans('global.backHome', array(), 'interface'),
        ));*/
    }

}
