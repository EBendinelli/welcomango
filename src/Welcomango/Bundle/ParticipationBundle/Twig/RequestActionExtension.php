<?php

namespace Welcomango\Bundle\ParticipationBundle\Twig;

use Symfony\Component\Routing\Router;

use Welcomango\Model\Participation;
use Welcomango\Model\User;

/**
 * Class RequestActionExtension
 */
class RequestActionExtension extends \Twig_Extension
{
    /**
     * @var Router $router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('request_action', [$this, 'requestAction'], ['is_safe' => ['html']]),
        );
    }

    /**
     * @param Participation $participation
     * @param User          $user
     *
     * @return string
     */
    public function requestAction(Participation $participation, User $user)
    {
        $availableActions = '';
        $messageRoute     = $this->router->generate('message_request', array(
            'participation_id' => $participation->getId(),
            'user_id'          => $user->getId(),
        ));


        //If checking received request
        if ($participation->getExperience()->getAuthor() == $user) {
            $acceptedRoute = $this->router->generate('participation_update', array('participation_id' => $participation->getId(), 'status' => 'Accepted'));
            $refusedRoute  = $this->router->generate('participation_update', array('participation_id' => $participation->getId(), 'status' => 'Refused'));

            // TODO change the accepted modal + content + confirmation
            switch ($participation->getStatus()) {
                case 'Requested':
                    $accept           = '<a href="'.$acceptedRoute.'"><i class="fa fa-check fa-2x text-success hover-opacity hover-pointer m-r-5"></i></a>';
                    $refuse           = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\''.$refusedRoute.'\')"><i class="fa fa-times fa-2x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    $message          = '<a href="'.$messageRoute.'"><i class="fa fa-envelope fa-2x text-black hover-opacity hover-pointer m-r-5"></i></a>';
                    $availableActions = $accept.$refuse.$message;
                    break;
                case 'Happened':
                case 'Accepted':
                    $message          = '<a href="'.$acceptedRoute.'"><i class="fa fa-envelope fa-2x text-black hover-opacity hover-pointer m-r-5"></i></a>';
                    $availableActions = $message;
                    break;
                case 'Refused':
                    $refused          = '<i class="fa fa-ban fa-2x m-r-5"></i>';
                    $availableActions = $refused;
                    break;
            }
        } else {
            //If checking sent requests
            $cancelRoute = $this->router->generate('participation_update', array('participation_id' => $participation->getId(), 'status' => 'Cancel'));
            switch ($participation->getStatus()) {
                case 'Requested':
                    $pending          = '<span><i class="fa fa-clock-o fa-2x text-black m-r-5"></i></span>';
                    $cancel           = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\''.$cancelRoute.'\')"><i class="fa fa-times fa-2x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    $availableActions = $pending.$cancel;
                    break;
                case 'Accepted':
                    $accepted         = '<span><i class="fa fa-check fa-2x text-success m-r-5"></i></span>';
                    $availableActions = $accepted;
                    break;
                case 'Refused':
                    $refused          = '<i class="fa fa-frown text-danger fa-2x m-r-5"></i>';
                    $availableActions = $refused;
                    break;
            }

            $message           = '<a href="'.$messageRoute.'"><i class="fa fa-envelope fa-2x text-black hover-opacity hover-pointer m-r-5"></i></a>';
            $availableActions .= $message;

        }

        return $availableActions;

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'request_action_extension';
    }
}
