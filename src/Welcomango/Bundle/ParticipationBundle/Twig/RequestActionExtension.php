<?php

namespace Welcomango\Bundle\ParticipationBundle\Twig;

use Welcomango\Model\User;

/**
 * Class RequestActionExtension
 */
class RequestActionExtension extends \Twig_Extension
{

    private $router;

    public function __construct($router)
    {
        $this->router               = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('request_action', array($this, 'requestAction'), array('is_safe' => array('html')))
        );
    }

    /**
     * @param mixed $role
     *
     * @return string
     */
    public function requestAction($participation)
    {
        $acceptedRoute = $this->router->generate('participation_update', array('participation_id' => $participation->getId(), 'status' => 'Accepted'));
        $refusedRoute = $this->router->generate('participation_update', array('participation_id' => $participation->getId(), 'status' => 'Refused'));

        // TODO change the accepted modal + content + confirmation
        $accepted = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\''.$acceptedRoute.'\')"><i class="fa fa-check-circle fa-2x text-success hover-opacity hover-pointer m-r-5"></i></span>';
        $refused = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\''.$refusedRoute.'\')"><i class="fa fa-times-circle fa-2x text-danger hover-opacity hover-pointer"></i></span>';

        return $accepted.$refused;

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'request_action_extension';
    }
}
