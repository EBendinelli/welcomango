<?php

namespace Welcomango\Bundle\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Booking;
use Welcomango\Model\Feedback;

/**
 * Class BookingController
 *
 * @ParamConverter("booking", options={"id" = "booking_id"})
 */
class BookingController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/requests/received", name="booking_received_list")
     * @Template()
     *
     * @return array
     */
    public function listReceivedAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $experiences = $user->getExperiences();

        $activeTab = $request->get('display');
        if(!$activeTab) $activeTab = 'received';

        if($activeTab == 'received'){ $statusFilter = array('Requested', 'Accepted', 'Refused'); }
        else{ $statusFilter = array('Happened'); }

        //Get User experiences Ids
        $experienceIds = array();
        $bookings = array();
        foreach($experiences as $experience){
            $experienceIds[] = $experience->getId();

            //Update booking status before display (if something happened)
            // This might not be useful once we have a cron
            $this->get('welcomango.front.booking.manager')->updateBookingStatus($experience);
        }

        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Booking')->findBy(array('experience' => $experienceIds , 'status' => $statusFilter), array('createdAt' => 'DESC'));
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        //Prepare feedback form
        $ratingForm = $this->createForm($this->get('welcomango.form.feedback.type'));

        return array(
            'bookings'   => $pagination,
            'activeTab'  => $activeTab,
            'user'       => $user,
            'ratingForm' => $ratingForm->createView(),
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/requests/sent", name="booking_sent_list")
     * @Template()
     *
     * @return array
     */
    public function listSentAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $activeTab = $request->get('display');
        if(!$activeTab) $activeTab = 'sent';

        if($activeTab == 'sent'){ $statusFilter = array('Requested', 'Accepted', 'Refused'); }
        else{ $statusFilter = array('Happened'); }

        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Booking')->findBy(array('user' => $user, 'status' => $statusFilter), array('createdAt' => 'DESC'));
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        //Prepare feedback form
        $ratingForm = $this->createForm($this->get('welcomango.form.feedback.type'));

        return array(
            'bookings' => $pagination,
            'activeTab' => $activeTab,
            'ratingForm' => $ratingForm->createView(),
        );
    }

    /**
     * @param Request    $request
     * @param Booking $booking
     *
     * @Route("/booking/{booking_id}", name="booking_sent_view")
     * @Template()
     *
     * @return array
     */
    public function viewSentAction(Request $request, Booking $booking)
    {
        $user = $this->getUser();
        if($user != $booking->getUser()){
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $feedbackForm = $this->createForm($this->get('welcomango.form.feedback.type'));

        return array(
            'booking' => $booking,
            'form' => $feedbackForm->createView(),
        );
    }

    /**
     * @param Request    $request
     * @param Booking $booking
     *
     * @Route("/received/{booking_id}", name="booking_received_view")
     * @Template()
     *
     * @return array
     */
    public function viewReceivedAction(Request $request, Booking $booking)
    {
        $user = $this->getUser();
        if($user != $booking->getExperience()->getCreator()){
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $feedbackForm = $this->createForm($this->get('welcomango.form.feedback.type'));

        return array(
            'booking' => $booking,
            'form' => $feedbackForm->createView(),
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/booking/create", name="booking_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $booking = new Booking();

        $form = $this->createForm($this->get('welcomango.form.booking.create'), $booking);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            $this->addFlash('success', $this->trans('booking.created.success', array(), 'booking'));

            return $this->redirect($this->generateUrl('booking_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Booking $booking
     *
     * @Route("/request/update/{booking_id}/status/{view}", name="booking_update")
     * @Template()
     *
     * @return array
     */
    public function updateAction(Request $request, Booking $booking, $view)
    {
        $booking->setStatus($request->query->get('status'));
        $booking->setSeen(false);

        $this->getDoctrine()->getManager()->persist($booking);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $this->trans('booking.action.message', array('%status%' => $booking->getStatus()), 'interface'));

        //We get the user who made the request
        $bookingUser = $booking->getUser();
        $user = $this->getUser();

        //And we send him a mail about the status update
        $message = \Swift_Message::newInstance()
            ->setSubject($this->trans('email.booking.requestUpdated', array('%status%' => $booking->getStatus()), 'interface'))
            ->setFrom('no-reply@welcomango.com')
            ->setTo($bookingUser->getEmail())
            ->setBody(
                $this->renderView(
                    'WelcomangoEmailBundle:EmailTemplate:requestUpdated.html.twig',[
                    'booking' => $booking,
                ]),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $thread = $booking->getThread();

        if($booking->getStatus() == 'Accepted'){
            $this->addFlash('success', $this->trans('booking.action.acceptedMessage', array('%user%' => $user->getFullName()), 'interface'));
            return $this->redirect($this->generateUrl('message_thread_view', ['user_id' => $user->getId(), 'thread_id' => $thread->getId()]));
        }

        if(substr($view, -5) == '_view'){
            return $this->redirect($this->generateUrl($view, ['booking_id' => $booking->getId()]));
        }else{
            return $this->redirect($this->generateUrl($view));
        }
    }

    /**
     * @param Request    $request
     * @param Booking $booking
     *
     * @Route("/request/update/{booking_id}/rate/{view}", name="booking_rate")
     * @Template()
     *
     * @return array
     */
    public function rateAction(Request $request, Booking $booking, $view)
    {
        $feedbackForm = $this->createForm($this->get('welcomango.form.feedback.type'));
        $feedbackForm->handleRequest($request);

        if ($feedbackForm->isValid()) {
            $user = $this->getUser();

            $comment = $feedbackForm->get('comment')->getData();
            $note = $feedbackForm->get('note')->getData();

            $this->get('welcomango.front.feedback.manager')->createFeedback($booking, $user, $comment, $note);
        }

        if(substr($view, -5) == '_view'){
            return $this->redirect($this->generateUrl($view, ['booking_id' => $booking->getId()]));
        }else{
            return $this->redirect($this->generateUrl($view, ['display' => 'happened']));
        }
    }

    /**
     * Check User Form filed for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/booking/form/list.json", name="booking_mark_as_seen_ajax", defaults={"_format"="json"})
     *
     * @return String
     */
    public function markAsSeenAction(Request $request){
        if ($request->request->has('id') && $request->request->get('id') != '' ) {
            $bookingRepository = $this->getDoctrine()->getManager()->getRepository('Welcomango\Model\Booking');
            $booking = $bookingRepository->find($request->request->get('id'));
            $booking->setSeen(true);

            $this->getDoctrine()->getManager()->persist($booking);
            $this->getDoctrine()->getManager()->flush();
        }
        return new JsonResponse();
    }

}
