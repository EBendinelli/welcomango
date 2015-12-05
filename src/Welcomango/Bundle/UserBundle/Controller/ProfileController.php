<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Controller\ProfileController as BaseProfileController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Welcomango\Model\Experience;

class ProfileController extends BaseProfileController
{

    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userExperiences = $user->getExperiences();

        $bookings = array();
        foreach($userExperiences as $experience){
            $expBookings = $experience->getBookings();
            $bookings = array_merge($bookings, $expBookings->toArray());
        }

        //This get the next visit given by the user. It's basically the accepted booking with the date the closest to today
        $nextVisitGiven = false;
        $nextVisitGivenTime = new \DateTime();
        $nextVisitGivenTime->add(new \DateInterval('P1Y'));
        foreach($bookings as $booking){
            if($booking->getStatus() == 'Accepted'){
                if($booking->getStartDatetime() < $nextVisitGivenTime){
                    $nextVisitGivenTime = $booking->getStartDatetime();
                    $nextVisitGiven = $booking;
                    dump($booking);
                }
            }
        }

        //This get the next visit planned by the user as a voyager.
        $nextTrip = false;
        $nextTripTime = new \DateTime();
        $nextTripTime->add(new \DateInterval('P1Y'));
        $plannedBookings = $user->getBookings();
        foreach($plannedBookings as $booking){
            if($booking->getStatus() == 'Accepted'){
                if($booking->getStartDatetime() < $nextTripTime){
                    $nextTripTime = $booking->getStartDatetime();
                    $nextTrip = $booking;
                    dump($booking);
                }
            }
        }

        //Get Comments
        $comments = $user->getReceivedComments();

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'userExperiences'   => $userExperiences,
            'nextVisitGiven'    => $nextVisitGiven,
            'nextTrip'          => $nextTrip,
            'comments'          => $comments,
        ));
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm($this->get('welcomango.front.form.user.edit.type'), $user);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);
            $this->addFlash('success', 'profile.edit.success');

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    protected function getRedirectionUrl(UserInterface $user)
    {
        // Change the redirection target after saving the profile
        return $this->container->get('router')->generate('fos_user_profile_edit');
    }

}
