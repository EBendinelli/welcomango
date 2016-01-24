<?php

namespace Welcomango\Bundle\BookingBundle\Twig;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

use Welcomango\Model\Booking;
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
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @param Router $router
     */
    public function __construct($router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('request_action', [$this, 'requestAction'], [
                'is_safe' => ['html'],
                'needs_environment' => true
                ]
            ));
    }

    /**
     * @param Booking $booking
     * @param User          $user
     *
     * @return string
     */
    public function requestAction(\Twig_Environment $twig, Booking $booking, User $user, $size = 2, $view = 'list')
    {
        $availableActions = '';
        $messageRoute     = $this->router->generate('message_request', array(
            'booking_id' => $booking->getId(),
            'user_id'    => $user->getId(),
        ));

        //If we are in the booking view and the experience already happened we don't display anything
        if($view == 'view' && $booking->getStatus() == 'Happened'){
            return '';
        }

        //If checking received request (the user is the creator of the experience, the local)
        if ($booking->getExperience()->getCreator() == $user) {
            if($view == "view"){
                $redirectRoute = 'booking_received_view';
            }else{
                $redirectRoute = 'booking_received_list';
            }
            $acceptedRoute = $this->router->generate('booking_update', array('booking_id' => $booking->getId(), 'status' => 'Accepted', 'view' => $redirectRoute));
            $refusedRoute  = $this->router->generate('booking_update', array('booking_id' => $booking->getId(), 'status' => 'Refused', 'view' => $redirectRoute));
            $ratingRoute  = $this->router->generate('booking_rate', array('booking_id' => $booking->getId(), 'view' => 'booking_received_list'));

            // TODO change the accepted modal + content + confirmation
            switch ($booking->getStatus()) {
                case 'Requested':
                    $accept           = '<a href="'.$acceptedRoute.'"><i class="fa fa-check fa-'.$size.'x text-success hover-opacity hover-pointer m-r-5"></i></a>';
                    if($view == "view") {
                        $refuse = '<span data-toggle="modal" data-target=".modal-action"><i class="fa fa-times fa-' . $size . 'x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    }else {
                        $refuse = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\'' . $refusedRoute . '\')"><i class="fa fa-times fa-' . $size . 'x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    }

                    $message          = '<a href="'.$messageRoute.'"><i class="fa fa-envelope fa-'.$size.'x text-black hover-opacity hover-pointer m-r-5"></i></a>';
                    $availableActions = $accept.$refuse.$message;
                    break;
                case 'Happened':
                    $feedback = $booking->getFeedbackFromLocal();
                    if($feedback){
                        if($feedback->isValidated()) {
                            $availableActions = $twig->render("WelcomangoCoreBundle:Core:note.html.twig", ['note' => $feedback->getNote(), 'size' => 'small']);
                        }else{
                            $availableActions = '<span style="font-style: italic">'.$this->translator->trans('interface.waitingForValidation', array(), 'interface').'</span>';
                        }
                    }else{
                        $availableActions = '<a class="btn btn-sm btn-complete m-r-5" data-toggle="modal" data-target=".modal-rating" onClick="updateRatingModal(\''.$ratingRoute .'\')">Rate</a>';
                    }
                    break;
                case 'Accepted':
                    $message          = '<a href="'.$messageRoute.'"><i class="fa fa-envelope fa-'.$size.'x text-black hover-opacity hover-pointer m-r-5"></i></a>';
                    $availableActions = $message;
                    break;
                case 'Refused':
                    $refused          = '<i class="fa fa-ban fa-'.$size.'x m-r-5"></i>';
                    $availableActions = $refused;
                    break;
            }
        } else {
            if($view == "view"){
                $redirectRoute = 'booking_sent_view';
            }else{
                $redirectRoute = 'booking_sent_list';
            }

            //If checking sent requests
            $cancelRoute = $this->router->generate('booking_update', array('booking_id' => $booking->getId(), 'status' => 'Cancel', 'view' => $redirectRoute));
            $ratingRoute  = $this->router->generate('booking_rate', array('booking_id' => $booking->getId(), 'view' => 'booking_sent_list'));
            switch ($booking->getStatus()) {
                case 'Requested':
                    $pending          = '<span><i class="fa fa-clock-o fa-'.$size.'x text-black m-r-5"></i></span>';
                    if($view == "view") {
                        $cancel = '<span data-toggle="modal" data-target=".modal-action"><i class="fa fa-times fa-' . $size . 'x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    }else{
                        $cancel = '<span data-toggle="modal" data-target=".modal-action" onClick="updateModal(\'' . $cancelRoute . '\')"><i class="fa fa-times fa-' . $size . 'x text-danger hover-opacity hover-pointer m-r-10"></i></span>';
                    }
                    $availableActions = $pending.$cancel;
                    break;
                case 'Happened':
                    $feedback = $booking->getFeedbackFromTraveler();
                    if($feedback){
                        if($feedback->isValidated()) {
                            $availableActions = $twig->render("WelcomangoCoreBundle:Core:note.html.twig", ['note' => $feedback->getNote(), 'size' => 'small']);
                        }else{
                            $availableActions = '<span style="font-style: italic">'.$this->translator->trans('interface.waitingForValidation', array(), 'interface').'</span>';
                        }
                    }else{
                        $availableActions = '<a class="btn btn-sm btn-complete m-r-5" data-toggle="modal" data-target=".modal-rating" onClick="updateRatingModal(\''.$ratingRoute .'\')">Rate</a>';
                    }
                    break;
                case 'Accepted':
                    $accepted         = '<span><i class="fa fa-check fa-'.$size.'x text-success m-r-5"></i></span>';
                    $availableActions = $accepted;
                    break;
                case 'Refused':
                    $refused          = '<i class="fa fa-frown text-danger fa-'.$size.'x m-r-5"></i>';
                    $availableActions = $refused;
                    break;
            }

            $message           = '<a href="'.$messageRoute.'"><i class="fa fa-envelope fa-'.$size.'x text-black hover-opacity hover-pointer m-r-5"></i></a>';
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
