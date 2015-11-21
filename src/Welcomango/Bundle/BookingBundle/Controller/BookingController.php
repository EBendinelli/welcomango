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


        $experienceIds = array();
        foreach($experiences as $experience){
            $experienceIds[] = $experience->getId();
        }
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Booking')->findBy(array('experience' => $experienceIds , 'status' => $statusFilter), array('createdAt' => 'DESC'));
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return array(
            'bookings' => $pagination,
            'activeTab' => $activeTab,
            'user' => $user
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

        return array(
            'bookings' => $pagination,
            'activeTab' => $activeTab,
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
     * @Route("/request/update/{booking_id}/status", name="booking_update")
     * @Template()
     *
     * @return array
     */
    public function updateAction(Request $request, Booking $booking)
    {
        $booking->setStatus($request->query->get('status'));

        if($request->query->get('status') == 'Accepted'){
            $booking->setSeen(false);
        }

        $this->getDoctrine()->getManager()->persist($booking);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $this->trans('booking.edit.success', array(), 'crm'));

        return $this->redirect($this->generateUrl('booking_received_list'));
    }

    /**
     * Check User Form filed for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/registration/form/list.json", name="booking_mark_as_seen_ajax", defaults={"_format"="json"})
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
