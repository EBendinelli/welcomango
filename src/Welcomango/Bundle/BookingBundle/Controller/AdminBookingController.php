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

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Booking;

/**
 * Class AdminBookingController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("booking", options={"id" = "booking_id"})
 */
class AdminBookingController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/booking/list", name="admin_booking_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Booking')->findAll();
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/booking/create", name="admin_booking_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm($this->get('welcomango.form.booking.create'), $booking, [
            'available_status' => $this->container->getParameter('available_status'),
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            $this->addFlash('success', $this->trans('booking.created.success', array(), 'booking'));

            return $this->redirect($this->generateUrl('admin_booking_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Booking $booking
     *
     * @Route("/booking/{booking_id}/edit", name="admin_booking_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Booking $booking)
    {
        $form = $this->createForm($this->get('welcomango.form.booking.create'), $booking, [
            'available_status' => $this->container->getParameter('available_status'),
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($booking);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('booking.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_booking_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_booking' => $booking
        );
    }

    /**
     * @param Booking $booking
     *
     * @Route("/booking/{booking_id}/delete", name="admin_booking_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Booking $booking)
    {

        $this->getDoctrine()->getManager()->remove($booking);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_booking_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/booking/_booking_search_ajax", name="admin_booking_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $booking = $this->getRepository('Welcomango\Model\Booking')->findByQuery($query);

        return array(
            'booking' => $booking
        );
    }
}
