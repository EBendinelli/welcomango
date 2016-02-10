<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Controller\ProfileController as BaseProfileController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Welcomango\Model\Experience;
use Welcomango\Model\Media;

/**
 * Class ProfileController
 */
class ProfileController extends BaseProfileController
{

    /**
     * Show the user
     *
     * @return Response
     */
    public function showAction()
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $entityManager   = $this->getDoctrine()->getManager();
        $userExperiences = $user->getExperiences();

        //Ii no experience we suggest to create one
        $createExperience = false;
        if($userExperiences->isEmpty()){
            $createExperience = true;
        }

        //Get booking for the user's experiences
        //And check if user has an experience which has been approved or refused
        $moderatedExperiences = array();
        $bookings             = array();

        foreach ($userExperiences as $experience) {
            $expBookings = $experience->getBookings();
            $bookings    = array_merge($bookings, $expBookings->toArray());
            if ($experience->hasUpdatedStatus()) {
                $moderatedExperiences[] = $experience;
            }
        }

        //This get the next visit given by the user. It's basically the accepted booking with the date the closest to today
        $nextVisitGiven     = false;
        $nextVisitGivenTime = new \DateTime();
        $nextVisitGivenTime->add(new \DateInterval('P1Y'));
        foreach ($bookings as $booking) {
            if ($booking->getStatus() == 'Accepted') {
                if ($booking->getStartDatetime() < $nextVisitGivenTime) {
                    $nextVisitGivenTime = $booking->getStartDatetime();
                    $nextVisitGiven     = $booking;
                }
            }
        }

        //This get the next visit planned by the user as a voyager.
        $nextTrip     = false;
        $nextTripTime = new \DateTime();
        $nextTripTime->add(new \DateInterval('P1Y'));
        $plannedBookings = $user->getBookings();
        foreach ($plannedBookings as $booking) {
            if ($booking->getStatus() == 'Accepted') {
                if ($booking->getStartDatetime() < $nextTripTime) {
                    $nextTripTime = $booking->getStartDatetime();
                    $nextTrip     = $booking;
                }
            }
        }

        //If the user has a new request we display a link
        $newRequest = false;
        foreach ($bookings as $booking) {
            if ($booking->getStatus() == 'Requested') {
                $newRequest = $booking;
            }
        }

        //Check if the user has unrated finished booking
        $feedbackAsLocal = array();
        foreach ($bookings as $booking) {
            if ($booking->getStatus() == 'Happened' && !$booking->hasFeedbackFromLocal()) {
                $feedbackAsLocal[] = $booking;
            }
        }
        if (count($feedbackAsLocal) === 1) {
            $feedbackAsLocal = $feedbackAsLocal[0];
        }

        $feedbackAsTraveler = array();
        foreach ($plannedBookings as $booking) {
            if ($booking->getStatus() == 'Happened' && !$booking->hasFeedbackFromTraveler()) {
                $feedbackAsTraveler[] = $booking;
            }
        }
        if (count($feedbackAsTraveler) === 1) {
            $feedbackAsTraveler = $feedbackAsTraveler[0];
        }

        //Check profile completion
        $desc = $user->getDescription();
        $lang = $user->getSpokenLanguages();
        $completeProfile = false;
        if(empty($desc) || empty($lang)){
            $completeProfile = true;
        }

        //Get Comments
        $feedbacks = $user->getReceivedFeedbacks();

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'userExperiences'      => $userExperiences,
            'nextVisitGiven'       => $nextVisitGiven,
            'nextTrip'             => $nextTrip,
            'feedbacks'            => $feedbacks,
            'newRequest'           => $newRequest,
            'feedbackAsLocal'      => $feedbackAsLocal,
            'feedbackAsTraveler'   => $feedbackAsTraveler,
            'moderatedExperiences' => $moderatedExperiences,
            'completeProfile'      => $completeProfile,
            'createExperience'    => $createExperience
        ));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        //This is used to determine the active tab
        $activeTab = $request->get('activeTab');
        if (!$activeTab) {
            $activeTab = 'current';
        }

        $originalSpokenLanguages = new ArrayCollection();
        foreach ($user->getSpokenLanguages() as $spokenLanguage) {
            $originalSpokenLanguages->add($spokenLanguage);
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $passwordFormFactory = $this->get('fos_user.change_password.form.factory');

        $passwordForm = $passwordFormFactory->createForm();
        $passwordForm->setData($user);

        $form = $this->createForm($this->get('welcomango.front.form.user.edit.type'), $user);
        $form->setData($user);


        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            foreach ($originalSpokenLanguages as $originalSpokenLanguage) {
                if (false === $user->getSpokenLanguages()->contains($originalSpokenLanguage)) {
                    $this->getDoctrine()->getManager()->remove($originalSpokenLanguage);
                }
            }

            $spokenLanguages = $this->get('welcomango.front.user.manager')->uniqueSpokenLanguage($user->getSpokenLanguages());
            $user->setSpokenLanguages($spokenLanguages);

            $cityManager = $this->get('welcomango.front.city.manager');
            $currentCity = $cityManager->checkAndCreateNewCity($form->get('currentCity'));

            $user->setCurrentCity($currentCity);

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            if ($form->getData()->getProfileMedia()) {
                $currentMedia = $form->getData()->getProfileMedia()->getOriginalFilename();
                $oldMedia     = $form->get("oldOriginalFilename")->getData();
                if ($currentMedia != $oldMedia) {
                    $this->get('welcomango.media.manager')->generateSimpleMedia($user, $oldMedia);
                }
            }

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url      = $this->generateUrl('fos_user_profile_edit');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            //change Tab if necessary
            if($activeTab != 'current'){
                $url = $response->getTargetUrl();
                $response->setTargetUrl($url.'?activeTab='.$activeTab);
            }

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form'         => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
            'activeTab'    => $activeTab,
        ));
    }

    protected function getRedirectionUrl(UserInterface $user)
    {
        // Change the redirection target after saving the profile
        return $this->container->get('router')->generate('fos_user_profile_edit');
    }
}
