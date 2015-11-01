<?php

namespace Welcomango\Bundle\ParticipationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Participation;

/**
 * Class AdminParticipationController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("participation", options={"id" = "participation_id"})
 */
class AdminParticipationController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/participation/list", name="admin_participation_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Participation')->findAll();
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
     * @Route("/participation/create", name="admin_participation_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $participation = new Participation();
        $form = $this->createForm($this->get('welcomango.form.participation.create'), $participation, [
            'available_status' => $this->container->getParameter('available_status'),
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            $this->addFlash('success', $this->trans('participation.created.success', array(), 'participation'));

            return $this->redirect($this->generateUrl('admin_participation_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Participation $participation
     *
     * @Route("/participation/{participation_id}/edit", name="admin_participation_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Participation $participation)
    {
        $form = $this->createForm($this->get('welcomango.form.participation.create'), $participation, [
            'available_status' => $this->container->getParameter('available_status'),
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($participation);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('participation.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_participation_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_participation' => $participation
        );
    }

    /**
     * @param Participation $participation
     *
     * @Route("/participation/{participation_id}/delete", name="admin_participation_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Participation $participation)
    {

        $this->getDoctrine()->getManager()->remove($participation);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_participation_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/participation/_participation_search_ajax", name="admin_participation_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $participations = $this->getRepository('Welcomango\Model\Participation')->findByQuery($query);

        return array(
            'participation' => $participations
        );
    }
}
